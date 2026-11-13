<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Piso extends Model
{
    protected $guarded = [];

    protected $casts = ['estado' => 'boolean'];

    public function sectores()
    {
        return $this->hasMany(Sector::class);
    }
}
