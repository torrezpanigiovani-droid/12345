<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $guarded = [];

    protected $casts = ['pagado_at' => 'datetime', 'estado' => 'boolean'];

    public function movimiento()
    {
        return $this->belongsTo(Movimiento::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
