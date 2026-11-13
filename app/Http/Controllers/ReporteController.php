<?php

namespace App\Http\Controllers;

use App\Exports\ReporteExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReporteController extends Controller
{
    private const TIPOS = ['ingresos_dia', 'ingresos_mes', 'frecuentes', 'ocupacion'];

    public function index()
    {
        return view('reportes.index');
    }

    /** JSON para los filtros dinámicos (jQuery / AJAX). */
    public function datos(Request $request)
    {
        return response()->json($this->generar($request));
    }

    public function pdf(Request $request)
    {
        $r = $this->generar($request);

        return Pdf::loadView('reportes.pdf', ['r' => $r, 'generado' => now()->format('d/m/Y H:i')])
            ->setPaper('a4', 'portrait')
            ->download('reporte-' . $request->get('tipo', 'ingresos_dia') . '.pdf');
    }

    public function excel(Request $request)
    {
        $r = $this->generar($request);

        return Excel::download(
            new ReporteExport($r['columnas'], $r['filas']),
            'reporte-' . $request->get('tipo', 'ingresos_dia') . '.xlsx'
        );
    }

    private function generar(Request $request): array
    {
        $tipo = in_array($request->get('tipo'), self::TIPOS, true) ? $request->get('tipo') : 'ingresos_dia';
        $desde = ($request->get('desde') ?: now()->startOfMonth()->toDateString()) . ' 00:00:00';
        $hasta = ($request->get('hasta') ?: now()->toDateString()) . ' 23:59:59';

        $efectivo = "SUM(CASE WHEN metodo = 'efectivo' THEN monto ELSE 0 END)";
        $qr = "SUM(CASE WHEN metodo = 'qr' THEN monto ELSE 0 END)";

        switch ($tipo) {
            case 'ingresos_mes':
                $rows = DB::table('pagos')->where('estado', true)->whereBetween('pagado_at', [$desde, $hasta])
                    ->selectRaw("DATE_FORMAT(pagado_at, '%Y-%m') AS periodo, COUNT(*) AS transacciones, $efectivo AS efectivo, $qr AS qr, SUM(monto) AS total")
                    ->groupBy('periodo')->orderBy('periodo')->get();
                $titulo = 'Ingresos por mes';
                $columnas = ['Mes', 'Transacciones', 'Efectivo', 'QR', 'Total'];
                $grafico = ['valor' => 4, 'titulo' => 'Ingresos'];
                break;

            case 'frecuentes':
                $rows = DB::table('movimientos as m')->join('vehiculos as v', 'v.id', '=', 'm.vehiculo_id')
                    ->whereBetween('m.entrada_at', [$desde, $hasta])
                    ->selectRaw('v.placa, v.tipo, v.marca, COUNT(*) AS visitas, COALESCE(SUM(m.monto), 0) AS total_pagado, COALESCE(ROUND(AVG(m.minutos)), 0) AS permanencia_prom')
                    ->groupBy('v.id', 'v.placa', 'v.tipo', 'v.marca')->orderByDesc('visitas')->limit(20)->get();
                $titulo = 'Vehículos más frecuentes';
                $columnas = ['Placa', 'Tipo', 'Marca', 'Visitas', 'Total pagado', 'Permanencia prom. (min)'];
                $grafico = ['valor' => 3, 'titulo' => 'Visitas'];
                break;

            case 'ocupacion':
                $rows = DB::table('movimientos')->whereBetween('entrada_at', [$desde, $hasta])
                    ->selectRaw("DATE(entrada_at) AS dia, COUNT(*) AS entradas, SUM(CASE WHEN estado = 'finalizado' THEN 1 ELSE 0 END) AS salidas, COALESCE(ROUND(AVG(minutos)), 0) AS permanencia_prom, COALESCE(SUM(monto), 0) AS facturado")
                    ->groupBy('dia')->orderBy('dia')->get();
                $titulo = 'Ocupación histórica';
                $columnas = ['Día', 'Entradas', 'Salidas', 'Permanencia prom. (min)', 'Facturado'];
                $grafico = ['valor' => 1, 'titulo' => 'Entradas'];
                break;

            default: // ingresos_dia
                $rows = DB::table('pagos')->where('estado', true)->whereBetween('pagado_at', [$desde, $hasta])
                    ->selectRaw("DATE(pagado_at) AS periodo, COUNT(*) AS transacciones, $efectivo AS efectivo, $qr AS qr, SUM(monto) AS total")
                    ->groupBy('periodo')->orderBy('periodo')->get();
                $titulo = 'Ingresos por día';
                $columnas = ['Día', 'Transacciones', 'Efectivo', 'QR', 'Total'];
                $grafico = ['valor' => 4, 'titulo' => 'Ingresos'];
        }

        return [
            'tipo' => $tipo,
            'titulo' => $titulo,
            'periodo' => substr($desde, 0, 10) . ' al ' . substr($hasta, 0, 10),
            'columnas' => $columnas,
            'filas' => $rows->map(fn ($r) => array_values((array) $r))->all(),
            'grafico' => $grafico,
        ];
    }
}
