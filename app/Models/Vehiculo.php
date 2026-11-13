<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    protected $guarded = [];

    protected $casts = ['estado' => 'boolean'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class);
    }
}
