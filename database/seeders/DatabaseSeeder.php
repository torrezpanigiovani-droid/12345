<?php

namespace Database\Seeders;

use App\Models\Celda;
use App\Models\Cliente;
use App\Models\Piso;
use App\Models\Sector;
use App\Models\Tarifa;
use App\Models\User;
use App\Models\Vehiculo;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Usuarios de prueba (la contraseña se encripta con el cast "hashed")
        foreach ([
            ['Administrador', 'admin@parking.test', 'administrador'],
            ['Operador Caja', 'operador@parking.test', 'operador'],
            ['Supervisor', 'supervisor@parking.test', 'supervisor'],
        ] as [$name, $email, $rol]) {
            User::updateOrCreate(['email' => $email], [
                'name' => $name, 'password' => 'password123', 'rol' => $rol, 'estado' => true,
            ]);
        }

        // Pisos, sectores y celdas
        $piso1 = Piso::firstOrCreate(['nombre' => 'Piso 1'], ['descripcion' => 'Planta baja']);
        $piso2 = Piso::firstOrCreate(['nombre' => 'Piso 2'], ['descripcion' => 'Primer nivel']);

        $plan = [
            [$piso1, 'A', 'auto', 8],
            [$piso1, 'B', 'moto', 6],
            [$piso1, 'C', 'camioneta', 4],
            [$piso2, 'D', 'todos', 8],
        ];
        foreach ($plan as [$piso, $sectorNombre, $tipo, $cantidad]) {
            $sector = Sector::firstOrCreate(['piso_id' => $piso->id, 'nombre' => $sectorNombre]);
            for ($i = 1; $i <= $cantidad; $i++) {
                Celda::firstOrCreate(
                    ['codigo' => sprintf('%s-%02d', $sectorNombre, $i)],
                    ['sector_id' => $sector->id, 'tipo_vehiculo' => $tipo]
                );
            }
        }

        // Tarifas (una por tipo de vehículo, mostrando las tres modalidades)
        if (Tarifa::count() === 0) {
            Tarifa::create(['tipo_vehiculo' => 'auto', 'modalidad' => 'hora', 'precio' => 3.00]);
            Tarifa::create(['tipo_vehiculo' => 'moto', 'modalidad' => 'fraccion', 'precio' => 0.50, 'fraccion_minutos' => 15]);
            Tarifa::create(['tipo_vehiculo' => 'camioneta', 'modalidad' => 'minuto', 'precio' => 0.10]);
        }

        // Cliente abonado de ejemplo
        $cliente = Cliente::firstOrCreate(['documento' => '1234567'], [
            'nombre' => 'Cliente Abonado Demo', 'telefono' => '70000000', 'tipo' => 'abonado',
            'convenio' => 'Mensualidad oficina', 'fecha_inicio' => today(), 'fecha_fin' => today()->addMonth(),
        ]);
        Vehiculo::firstOrCreate(['placa' => '1234ABC'], [
            'cliente_id' => $cliente->id, 'marca' => 'Toyota', 'modelo' => 'Corolla', 'color' => 'Blanco', 'tipo' => 'auto',
        ]);
    }
}
