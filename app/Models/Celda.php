<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Celda extends Model
{
    protected $guarded = [];

    protected $casts = ['estado' => 'boolean', 'ocupada' => 'boolean'];

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class);
    }
}
