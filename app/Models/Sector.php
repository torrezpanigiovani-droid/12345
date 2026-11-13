<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    protected $table = 'sectores';

    protected $guarded = [];

    protected $casts = ['estado' => 'boolean'];

    public function piso()
    {
        return $this->belongsTo(Piso::class);
    }

    public function celdas()
    {
        return $this->hasMany(Celda::class);
    }
}
