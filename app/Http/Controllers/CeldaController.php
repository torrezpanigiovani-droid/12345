<?php

namespace App\Http\Controllers;

use App\Models\Celda;
use App\Models\Sector;

class CeldaController extends CrudController
{
    protected string $model = Celda::class;
    protected string $titulo = 'Celdas';
    protected string $ruta = 'celdas';
    protected array $with = ['sector.piso'];

    protected function campos(): array
    {
        $sectores = Sector::with('piso')->where('estado', true)->get()
            ->mapWithKeys(fn ($s) => [$s->id => $s->piso->nombre . ' / Sector ' . $s->nombre])->all();

        return [
            ['name' => 'sector_id', 'label' => 'Piso / Sector', 'type' => 'select', 'col' => 6, 'options' => $sectores],
            ['name' => 'codigo', 'label' => 'Código de celda', 'type' => 'text', 'col' => 3],
            ['name' => 'tipo_vehiculo', 'label' => 'Tipo permitido', 'type' => 'select', 'col' => 3, 'options' => [
                'todos' => 'Todos', 'auto' => 'Auto', 'moto' => 'Moto', 'camioneta' => 'Camioneta',
            ]],
        ];
    }

    protected function columnas(): array
    {
        return [
            ['data' => 'codigo', 'label' => 'Celda'],
            ['data' => 'sector.piso.nombre', 'label' => 'Piso'],
            ['data' => 'sector.nombre', 'label' => 'Sector'],
            ['data' => 'tipo_vehiculo', 'label' => 'Tipo'],
            ['data' => 'ocupada', 'label' => 'Disponibilidad', 'type' => 'ocupada'],
        ];
    }

    protected function reglas(?int $id): array
    {
        return [
            'sector_id' => 'required|exists:sectores,id',
            'codigo' => 'required|string|max:20|unique:celdas,codigo' . ($id ? ",$id" : ''),
            'tipo_vehiculo' => 'required|in:todos,auto,moto,camioneta',
        ];
    }

    protected function antesGuardar(array $data, ?int $id): array
    {
        $data['codigo'] = strtoupper($data['codigo']);

        return $data;
    }

    protected function antesCambiarEstado($item): ?string
    {
        return $item->ocupada ? 'No puedes desactivar una celda ocupada.' : null;
    }
}
