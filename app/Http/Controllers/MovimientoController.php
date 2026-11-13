<?php

namespace App\Http\Controllers;

use App\Models\Celda;
use App\Models\Movimiento;
use App\Models\Pago;
use App\Models\Vehiculo;
use App\Support\Mensajes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** Control de entradas y salidas (con cobro en la salida). */
class MovimientoController extends Controller
{
    public function index()
    {
        return view('movimientos.index');
    }

    /** Vehículos actualmente dentro del estacionamiento. */
    public function activos()
    {
        return Movimiento::with(['vehiculo', 'celda.sector.piso'])
            ->where('estado', 'activo')->orderByDesc('entrada_at')->get()
            ->map(fn ($m) => [
                'id' => $m->id,
                'ticket' => $m->ticket,
                'placa' => $m->vehiculo->placa,
                'tipo' => $m->vehiculo->tipo,
                'celda' => $m->celda->codigo,
                'ubicacion' => $m->celda->sector->piso->nombre . ' / ' . $m->celda->sector->nombre,
                'entrada' => $m->entrada_at->format('d/m/Y H:i'),
                'minutos' => $m->minutosTranscurridos(),
            ]);
    }

    /** Celdas libres compatibles con el tipo de vehículo (para el select del formulario). */
    public function celdasLibres(Request $request)
    {
        $tipo = $request->get('tipo', 'auto');

        return Celda::with('sector.piso')
            ->where('estado', true)->where('ocupada', false)
            ->where(fn ($q) => $q->where('tipo_vehiculo', 'todos')->orWhere('tipo_vehiculo', $tipo))
            ->orderBy('codigo')->get()
            ->map(fn ($c) => [
                'id' => $c->id,
                'texto' => $c->codigo . ' — ' . $c->sector->piso->nombre . ' / ' . $c->sector->nombre,
            ]);
    }

    public function entrada(Request $request)
    {
        $d = $request->validate([
            'placa' => 'required|string|max:15',
            'tipo' => 'required|in:auto,moto,camioneta',
            'marca' => 'nullable|string|max:50',
            'modelo' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:30',
            'celda_id' => 'nullable|exists:celdas,id',
        ], Mensajes::validacion());

        $placa = strtoupper(preg_replace('/\s+/', '', $d['placa']));

        return DB::transaction(function () use ($d, $placa) {
            $vehiculo = Vehiculo::firstOrCreate(['placa' => $placa], [
                'tipo' => $d['tipo'], 'marca' => $d['marca'] ?? null,
                'modelo' => $d['modelo'] ?? null, 'color' => $d['color'] ?? null,
            ]);

            if (! $vehiculo->estado) {
                return response()->json(['message' => 'El vehículo está desactivado.'], 422);
            }
            if (Movimiento::where('vehiculo_id', $vehiculo->id)->where('estado', 'activo')->exists()) {
                return response()->json(['message' => "El vehículo $placa ya se encuentra dentro."], 422);
            }

            // Celda elegida manualmente o asignación automática de la primera libre compatible.
            $query = Celda::where('estado', true)->where('ocupada', false)
                ->where(fn ($q) => $q->where('tipo_vehiculo', 'todos')->orWhere('tipo_vehiculo', $vehiculo->tipo))
                ->lockForUpdate();
            $celda = ! empty($d['celda_id'])
                ? $query->where('id', $d['celda_id'])->first()
                : $query->orderBy('id')->first();

            if (! $celda) {
                return response()->json(['message' => 'No hay celdas disponibles para este vehículo.'], 422);
            }

            $mov = Movimiento::create([
                'ticket' => Movimiento::generarTicket(),
                'vehiculo_id' => $vehiculo->id,
                'celda_id' => $celda->id,
                'user_id' => auth()->id(),
                'entrada_at' => now(),
            ]);
            $celda->update(['ocupada' => true]);

            return response()->json([
                'ok' => true,
                'ticket' => $mov->ticket,
                'celda' => $celda->codigo,
                'url' => route('movimientos.ticket', $mov->id),
            ]);
        });
    }

    /** Busca por ticket o placa y calcula tiempo y tarifa (sin guardar nada). */
    public function calcular(Request $request)
    {
        $q = strtoupper(preg_replace('/\s+/', '', (string) $request->get('q')));

        $mov = Movimiento::with(['vehiculo.cliente', 'celda'])->where('estado', 'activo')
            ->where(fn ($w) => $w->where('ticket', $q)
                ->orWhereHas('vehiculo', fn ($v) => $v->where('placa', $q)))
            ->first();

        if (! $mov) {
            return response()->json(['message' => 'No se encontró un vehículo activo con ese ticket o placa.'], 404);
        }

        $minutos = $mov->minutosTranscurridos();
        [$monto, $tarifa] = $mov->calcularCobro($minutos);

        return response()->json([
            'id' => $mov->id,
            'ticket' => $mov->ticket,
            'placa' => $mov->vehiculo->placa,
            'tipo' => $mov->vehiculo->tipo,
            'celda' => $mov->celda->codigo,
            'entrada' => $mov->entrada_at->format('d/m/Y H:i'),
            'minutos' => $minutos,
            'tarifa' => $tarifa ? "{$tarifa->modalidad} · {$tarifa->precio}" . ($tarifa->modalidad === 'fraccion' ? " / {$tarifa->fraccion_minutos} min" : '') : 'Sin tarifa configurada',
            'abonado' => (bool) ($mov->vehiculo->cliente?->esAbonadoVigente()),
            'monto' => $monto,
        ]);
    }

    /** Registra la salida y el cobro (efectivo o QR). */
    public function salida(Request $request, int $id)
    {
        $d = $request->validate([
            'metodo' => 'required|in:efectivo,qr',
            'referencia' => 'nullable|string|max:80',
        ], Mensajes::validacion());

        return DB::transaction(function () use ($d, $id) {
            $mov = Movimiento::with('vehiculo.cliente')->lockForUpdate()->findOrFail($id);

            if ($mov->estado !== 'activo') {
                return response()->json(['message' => 'Este ticket ya fue cerrado.'], 422);
            }

            $ahora = now();
            $minutos = $mov->minutosTranscurridos($ahora);
            [$monto, $tarifa] = $mov->calcularCobro($minutos);

            $mov->update([
                'salida_at' => $ahora, 'minutos' => $minutos, 'monto' => $monto,
                'tarifa_id' => $tarifa?->id, 'estado' => 'finalizado',
            ]);
            $mov->celda()->update(['ocupada' => false]);

            Pago::create([
                'movimiento_id' => $mov->id, 'user_id' => auth()->id(), 'monto' => $monto,
                'metodo' => $d['metodo'], 'referencia' => $d['referencia'] ?? null, 'pagado_at' => $ahora,
            ]);

            return response()->json([
                'ok' => true, 'monto' => $monto, 'minutos' => $minutos,
                'url' => route('movimientos.ticket', $mov->id),
            ]);
        });
    }

    /** Ticket digital imprimible con código QR. */
    public function ticket(int $id)
    {
        $mov = Movimiento::with(['vehiculo', 'celda.sector.piso', 'user', 'pago'])->findOrFail($id);

        return view('movimientos.ticket', compact('mov'));
    }
}
