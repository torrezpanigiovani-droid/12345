<?php

namespace App\Http\Controllers;

use App\Models\User;

class UsuarioController extends CrudController
{
    protected string $model = User::class;
    protected string $titulo = 'Usuarios';
    protected string $ruta = 'usuarios';

    protected function campos(): array
    {
        return [
            ['name' => 'name', 'label' => 'Nombre completo', 'type' => 'text', 'col' => 6],
            ['name' => 'email', 'label' => 'Correo', 'type' => 'email', 'col' => 6],
            ['name' => 'telefono', 'label' => 'Teléfono', 'type' => 'text', 'col' => 4],
            ['name' => 'rol', 'label' => 'Rol', 'type' => 'select', 'col' => 4, 'options' => [
                'administrador' => 'Administrador', 'operador' => 'Operador / Cajero', 'supervisor' => 'Supervisor',
            ]],
            ['name' => 'password', 'label' => 'Contraseña (vacío = no cambiar)', 'type' => 'password', 'col' => 4],
        ];
    }

    protected function columnas(): array
    {
        return [
            ['data' => 'name', 'label' => 'Nombre'],
            ['data' => 'email', 'label' => 'Correo'],
            ['data' => 'telefono', 'label' => 'Teléfono'],
            ['data' => 'rol', 'label' => 'Rol'],
        ];
    }

    protected function reglas(?int $id): array
    {
        return [
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:120|unique:users,email' . ($id ? ",$id" : ''),
            'telefono' => 'nullable|string|max:30',
            'rol' => 'required|in:administrador,operador,supervisor',
            'password' => ($id ? 'nullable' : 'required') . '|string|min:6',
        ];
    }

    protected function antesGuardar(array $data, ?int $id): array
    {
        if (empty($data['password'])) {
            unset($data['password']);   // el cast "hashed" encripta al guardar
        }

        return $data;
    }

    protected function antesCambiarEstado($item): ?string
    {
        return $item->id === auth()->id() ? 'No puedes desactivar tu propio usuario.' : null;
    }
}
