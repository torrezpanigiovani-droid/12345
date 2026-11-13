<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $guarded = [];

    protected $casts = [
        'estado' => 'boolean',
        'fecha_inicio' => 'date:Y-m-d',
        'fecha_fin' => 'date:Y-m-d',
    ];

    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class);
    }

    /** Abonado activo con convenio vigente => no paga tarifa. */
    public function esAbonadoVigente(): bool
    {
        return $this->estado
            && $this->tipo === 'abonado'
            && (! $this->fecha_fin || $this->fecha_fin->gte(today()));
    }
}
