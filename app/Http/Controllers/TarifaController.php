<?php

namespace App\Http\Controllers;

use App\Models\Tarifa;

class TarifaController extends CrudController
{
    protected string $model = Tarifa::class;
    protected string $titulo = 'Tarifas';
    protected string $ruta = 'tarifas';

    protected function campos(): array
    {
        return [
            ['name' => 'tipo_vehiculo', 'label' => 'Tipo de vehículo', 'type' => 'select', 'col' => 6, 'options' => [
                'auto' => 'Auto', 'moto' => 'Moto', 'camioneta' => 'Camioneta',
            ]],
            ['name' => 'modalidad', 'label' => 'Modalidad de cobro', 'type' => 'select', 'col' => 6, 'options' => [
                'minuto' => 'Por minuto', 'hora' => 'Por hora (o fracción)', 'fraccion' => 'Por fracción de N minutos',
            ]],
            ['name' => 'precio', 'label' => 'Precio por unidad', 'type' => 'number', 'col' => 6, 'step' => '0.01'],
            ['name' => 'fraccion_minutos', 'label' => 'Minutos por fracción (solo "fracción")', 'type' => 'number', 'col' => 6],
        ];
    }

    protected function columnas(): array
    {
        return [
            ['data' => 'tipo_vehiculo', 'label' => 'Vehículo'],
            ['data' => 'modalidad', 'label' => 'Modalidad'],
            ['data' => 'precio', 'label' => 'Precio', 'type' => 'money'],
            ['data' => 'fraccion_minutos', 'label' => 'Fracción (min)'],
        ];
    }

    protected function reglas(?int $id): array
    {
        return [
            'tipo_vehiculo' => 'required|in:auto,moto,camioneta',
            'modalidad' => 'required|in:minuto,hora,fraccion',
            'precio' => 'required|numeric|min:0',
            'fraccion_minutos' => 'nullable|integer|min:1',
        ];
    }

    protected function antesGuardar(array $data, ?int $id): array
    {
        $data['fraccion_minutos'] = $data['fraccion_minutos'] ?? 15;

        return $data;
    }
}
