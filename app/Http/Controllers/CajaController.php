<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Http\Request;

class CajaController extends Controller
{
    public function index()
    {
        return view('caja.index');
    }

    /** Transacciones filtradas por fecha (AJAX). */
    public function data(Request $request)
    {
        $desde = $request->get('desde') ?: today()->toDateString();
        $hasta = $request->get('hasta') ?: today()->toDateString();

        $pagos = Pago::with(['movimiento.vehiculo', 'user'])
            ->where('estado', true)
            ->whereBetween('pagado_at', ["$desde 00:00:00", "$hasta 23:59:59"])
            ->orderByDesc('pagado_at')->get();

        return response()->json([
            'filas' => $pagos->map(fn ($p) => [
                'fecha' => $p->pagado_at->format('d/m/Y H:i'),
                'ticket' => $p->movimiento->ticket,
                'placa' => $p->movimiento->vehiculo->placa,
                'minutos' => $p->movimiento->minutos,
                'metodo' => $p->metodo,
                'referencia' => $p->referencia,
                'cajero' => $p->user->name,
                'monto' => (float) $p->monto,
            ])->values(),
            'totales' => [
                'efectivo' => (float) $pagos->where('metodo', 'efectivo')->sum('monto'),
                'qr' => (float) $pagos->where('metodo', 'qr')->sum('monto'),
                'total' => (float) $pagos->sum('monto'),
                'transacciones' => $pagos->count(),
            ],
        ]);
    }
}
