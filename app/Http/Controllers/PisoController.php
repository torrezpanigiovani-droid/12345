<?php

namespace App\Http\Controllers;

use App\Models\Piso;

class PisoController extends CrudController
{
    protected string $model = Piso::class;
    protected string $titulo = 'Pisos';
    protected string $ruta = 'pisos';

    protected function campos(): array
    {
        return [
            ['name' => 'nombre', 'label' => 'Nombre del piso', 'type' => 'text', 'col' => 12],
            ['name' => 'descripcion', 'label' => 'Descripción', 'type' => 'text', 'col' => 12],
        ];
    }

    protected function columnas(): array
    {
        return [
            ['data' => 'nombre', 'label' => 'Piso'],
            ['data' => 'descripcion', 'label' => 'Descripción'],
        ];
    }

    protected function reglas(?int $id): array
    {
        return [
            'nombre' => 'required|string|max:60|unique:pisos,nombre' . ($id ? ",$id" : ''),
            'descripcion' => 'nullable|string|max:200',
        ];
    }
}
