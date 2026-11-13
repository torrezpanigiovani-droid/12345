<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Vehiculo;

class VehiculoController extends CrudController
{
    protected string $model = Vehiculo::class;
    protected string $titulo = 'Vehículos';
    protected string $ruta = 'vehiculos';
    protected array $with = ['cliente'];

    protected function campos(): array
    {
        $clientes = ['' => '— Sin cliente —'] + Cliente::where('estado', true)->orderBy('nombre')->pluck('nombre', 'id')->all();

        return [
            ['name' => 'placa', 'label' => 'Placa', 'type' => 'text', 'col' => 4],
            ['name' => 'tipo', 'label' => 'Tipo', 'type' => 'select', 'col' => 4, 'options' => [
                'auto' => 'Auto', 'moto' => 'Moto', 'camioneta' => 'Camioneta',
            ]],
            ['name' => 'cliente_id', 'label' => 'Cliente asociado', 'type' => 'select', 'col' => 4, 'options' => $clientes],
            ['name' => 'marca', 'label' => 'Marca', 'type' => 'text', 'col' => 4],
            ['name' => 'modelo', 'label' => 'Modelo', 'type' => 'text', 'col' => 4],
            ['name' => 'color', 'label' => 'Color', 'type' => 'text', 'col' => 4],
        ];
    }

    protected function columnas(): array
    {
        return [
            ['data' => 'placa', 'label' => 'Placa'],
            ['data' => 'tipo', 'label' => 'Tipo'],
            ['data' => 'marca', 'label' => 'Marca'],
            ['data' => 'modelo', 'label' => 'Modelo'],
            ['data' => 'color', 'label' => 'Color'],
            ['data' => 'cliente.nombre', 'label' => 'Cliente'],
        ];
    }

    protected function reglas(?int $id): array
    {
        return [
            'placa' => 'required|string|max:15|unique:vehiculos,placa' . ($id ? ",$id" : ''),
            'tipo' => 'required|in:auto,moto,camioneta',
            'cliente_id' => 'nullable|exists:clientes,id',
            'marca' => 'required|string|max:50',
            'modelo' => 'required|string|max:50',
            'color' => 'required|string|max:30',
        ];
    }

    protected function antesGuardar(array $data, ?int $id): array
    {
        $data['placa'] = strtoupper(preg_replace('/\s+/', '', $data['placa']));

        return $data;
    }
}
