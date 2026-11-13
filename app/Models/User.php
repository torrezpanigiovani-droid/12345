<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'rol', 'telefono', 'estado'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'estado' => 'boolean',
        ];
    }

    /** El administrador siempre tiene acceso; los demás deben estar en la lista. */
    public function esRol(string ...$roles): bool
    {
        return $this->rol === 'administrador' || in_array($this->rol, $roles, true);
    }
}
