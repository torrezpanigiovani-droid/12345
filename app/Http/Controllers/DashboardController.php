<?php

namespace App\Http\Controllers;

use App\Models\Celda;
use App\Models\Movimiento;
use App\Models\Pago;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }

    /** Estado en tiempo real (consultado por AJAX cada pocos segundos). */
    public function stats()
    {
        $celdas = Celda::with('sector.piso')->where('estado', true)->orderBy('codigo')->get();

        return response()->json([
            'total' => $celdas->count(),
            'ocupadas' => $celdas->where('ocupada', true)->count(),
            'libres' => $celdas->where('ocupada', false)->count(),
            'dentro' => Movimiento::where('estado', 'activo')->count(),
            'entradas_hoy' => Movimiento::whereDate('entrada_at', today())->count(),
            'ingresos_hoy' => (float) Pago::where('estado', true)->whereDate('pagado_at', today())->sum('monto'),
            'celdas' => $celdas->map(fn ($c) => [
                'codigo' => $c->codigo,
                'ocupada' => $c->ocupada,
                'piso' => $c->sector->piso->nombre,
                'sector' => $c->sector->nombre,
            ])->values(),
        ]);
    }
}
