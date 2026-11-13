<?php

use App\Http\Controllers\CajaController;
use App\Http\Controllers\CeldaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\PisoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\TarifaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VehiculoController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

// ---------- Login ----------
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'show'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    // Roles: administrador (todo) | supervisor | operador
    $todos = 'administrador,supervisor,operador';

    Route::middleware(RoleMiddleware::class . ':' . $todos)->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');
    });

    // ---------- Módulos de mantenimiento (solo activar / desactivar) ----------
    $crud = function (string $slug, string $controller, string $roles) {
        Route::middleware(RoleMiddleware::class . ':' . $roles)->prefix($slug)->name($slug . '.')
            ->group(function () use ($controller) {
                Route::get('/', [$controller, 'index'])->name('index');
                Route::get('data', [$controller, 'data'])->name('data');
                Route::post('/', [$controller, 'store'])->name('store');
                Route::put('{id}', [$controller, 'update'])->name('update');
                Route::patch('{id}/estado', [$controller, 'toggle'])->name('toggle');
            });
    };

    $crud('usuarios', UsuarioController::class, 'administrador');
    $crud('clientes', ClienteController::class, $todos);
    $crud('vehiculos', VehiculoController::class, $todos);
    $crud('pisos', PisoController::class, 'administrador');
    $crud('sectores', SectorController::class, 'administrador');
    $crud('celdas', CeldaController::class, 'administrador,supervisor');
    $crud('tarifas', TarifaController::class, 'administrador');

    // ---------- Operativa: entradas / salidas ----------
    Route::middleware(RoleMiddleware::class . ':' . $todos)->prefix('movimientos')->name('movimientos.')->group(function () {
        Route::get('/', [MovimientoController::class, 'index'])->name('index');
        Route::get('activos', [MovimientoController::class, 'activos'])->name('activos');
        Route::get('celdas-libres', [MovimientoController::class, 'celdasLibres'])->name('celdas');
        Route::get('calcular', [MovimientoController::class, 'calcular'])->name('calcular');
        Route::post('entrada', [MovimientoController::class, 'entrada'])->name('entrada');
        Route::post('{id}/salida', [MovimientoController::class, 'salida'])->name('salida');
        Route::get('{id}/ticket', [MovimientoController::class, 'ticket'])->name('ticket');
    });

    // ---------- Caja ----------
    Route::middleware(RoleMiddleware::class . ':' . $todos)->prefix('caja')->name('caja.')->group(function () {
        Route::get('/', [CajaController::class, 'index'])->name('index');
        Route::get('data', [CajaController::class, 'data'])->name('data');
    });

    // ---------- Reportes (administrador y supervisor) ----------
    Route::middleware(RoleMiddleware::class . ':administrador,supervisor')->prefix('reportes')->name('reportes.')->group(function () {
        Route::get('/', [ReporteController::class, 'index'])->name('index');
        Route::get('datos', [ReporteController::class, 'datos'])->name('datos');
        Route::get('pdf', [ReporteController::class, 'pdf'])->name('pdf');
        Route::get('excel', [ReporteController::class, 'excel'])->name('excel');
    });
});
