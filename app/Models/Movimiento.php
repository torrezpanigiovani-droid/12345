<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected $guarded = [];

    protected $casts = [
        'entrada_at' => 'datetime',
        'salida_at' => 'datetime',
    ];

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }

    public function celda()
    {
        return $this->belongsTo(Celda::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pago()
    {
        return $this->hasOne(Pago::class);
    }

    public static function generarTicket(): string
    {
        return 'TCK-' . now()->format('ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(5));
    }

    /** Minutos transcurridos (mínimo 1). */
    public function minutosTranscurridos($hasta = null): int
    {
        $hasta = $hasta ?: now();

        return max(1, (int) ceil(($hasta->timestamp - $this->entrada_at->timestamp) / 60));
    }

    /**
     * Calcula el cobro según la tarifa vigente del tipo de vehículo.
     *  - minuto:   minutos × precio
     *  - hora:     horas o fracción de hora × precio
     *  - fraccion: bloques de N minutos × precio
     * Los abonados con convenio vigente no pagan.
     *
     * @return array{0: float, 1: ?Tarifa}
     */
    public function calcularCobro(int $minutos): array
    {
        $vehiculo = $this->vehiculo;
        $tarifa = Tarifa::where('tipo_vehiculo', $vehiculo->tipo)
            ->where('estado', true)->latest('id')->first();

        if (! $tarifa) {
            return [0.0, null];
        }
        if ($vehiculo->cliente && $vehiculo->cliente->esAbonadoVigente()) {
            return [0.0, $tarifa];
        }

        $unidades = match ($tarifa->modalidad) {
            'minuto' => $minutos,
            'hora' => (int) ceil($minutos / 60),
            default => (int) ceil($minutos / max(1, $tarifa->fraccion_minutos)),
        };

        return [round($unidades * (float) $tarifa->precio, 2), $tarifa];
    }
}
