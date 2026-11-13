<?php

namespace App\Http\Controllers;

use App\Models\Cliente;

class ClienteController extends CrudController
{
    protected string $model = Cliente::class;
    protected string $titulo = 'Clientes / Abonados';
    protected string $ruta = 'clientes';
    protected array $withCount = ['vehiculos'];

    protected function campos(): array
    {
        return [
            ['name' => 'nombre', 'label' => 'Nombre / Razón social', 'type' => 'text', 'col' => 6],
            ['name' => 'documento', 'label' => 'CI / NIT', 'type' => 'text', 'col' => 6],
            ['name' => 'telefono', 'label' => 'Teléfono', 'type' => 'text', 'col' => 4],
            ['name' => 'email', 'label' => 'Correo', 'type' => 'email', 'col' => 4],
            ['name' => 'tipo', 'label' => 'Tipo', 'type' => 'select', 'col' => 4, 'options' => [
                'habitual' => 'Habitual', 'abonado' => 'Abonado (mensualidad)',
            ]],
            ['name' => 'direccion', 'label' => 'Dirección', 'type' => 'text', 'col' => 12],
            ['name' => 'convenio', 'label' => 'Convenio / detalle', 'type' => 'text', 'col' => 6],
            ['name' => 'fecha_inicio', 'label' => 'Inicio convenio', 'type' => 'date', 'col' => 3],
            ['name' => 'fecha_fin', 'label' => 'Fin convenio', 'type' => 'date', 'col' => 3],
        ];
    }

    protected function columnas(): array
    {
        return [
            ['data' => 'nombre', 'label' => 'Nombre'],
            ['data' => 'documento', 'label' => 'Documento'],
            ['data' => 'telefono', 'label' => 'Teléfono'],
            ['data' => 'tipo', 'label' => 'Tipo'],
            ['data' => 'convenio', 'label' => 'Convenio'],
            ['data' => 'fecha_fin', 'label' => 'Vence'],
            ['data' => 'vehiculos_count', 'label' => 'Vehículos'],
        ];
    }

    protected function reglas(?int $id): array
    {
        return [
            'nombre' => 'required|string|max:120',
            'documento' => 'required|string|max:30|unique:clientes,documento' . ($id ? ",$id" : ''),
            'telefono' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:120',
            'direccion' => 'nullable|string|max:200',
            'tipo' => 'required|in:habitual,abonado',
            'convenio' => 'nullable|string|max:150',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        ];
    }
}
