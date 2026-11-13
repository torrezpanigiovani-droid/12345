<?php

namespace App\Http\Controllers;

use App\Models\Piso;
use App\Models\Sector;
use Illuminate\Validation\Rule;

class SectorController extends CrudController
{
    protected string $model = Sector::class;
    protected string $titulo = 'Sectores';
    protected string $ruta = 'sectores';
    protected array $with = ['piso'];

    protected function campos(): array
    {
        return [
            ['name' => 'piso_id', 'label' => 'Piso', 'type' => 'select', 'col' => 6,
                'options' => Piso::where('estado', true)->orderBy('nombre')->pluck('nombre', 'id')->all()],
            ['name' => 'nombre', 'label' => 'Sector', 'type' => 'text', 'col' => 6],
        ];
    }

    protected function columnas(): array
    {
        return [
            ['data' => 'piso.nombre', 'label' => 'Piso'],
            ['data' => 'nombre', 'label' => 'Sector'],
        ];
    }

    protected function reglas(?int $id): array
    {
        return [
            'piso_id' => 'required|exists:pisos,id',
            'nombre' => ['required', 'string', 'max:60',
                Rule::unique('sectores', 'nombre')->where('piso_id', request('piso_id'))->ignore($id)],
        ];
    }
}
