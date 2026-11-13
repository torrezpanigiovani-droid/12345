<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarifa extends Model
{
    protected $guarded = [];

    protected $casts = ['estado' => 'boolean'];
}
