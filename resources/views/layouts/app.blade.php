<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'Panel') · ParkControl</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <style>
        .celda { display:inline-block; width:64px; padding:8px 0; margin:3px; text-align:center; border-radius:6px; color:#fff; font-weight:600; font-size:.85rem; }
        .celda.libre { background:#28a745; } .celda.ocupada { background:#dc3545; }
    </style>
    @stack('styles')
</head>
@php $u = auth()->user(); $act = fn ($p) => request()->routeIs($p) ? 'active' : ''; @endphp
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a></li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item"><span class="nav-link"><i class="fas fa-user-circle"></i> {{ $u->name }} <span class="badge badge-info">{{ ucfirst($u->rol) }}</span></span></li>
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">@csrf
                    <button class="btn btn-link nav-link" type="submit"><i class="fas fa-sign-out-alt"></i> Salir</button>
                </form>
            </li>
        </ul>
    </nav>

    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="{{ route('dashboard') }}" class="brand-link text-center"><i class="fas fa-parking"></i> <span class="brand-text font-weight-light">ParkControl</span></a>
        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <li class="nav-item"><a href="{{ route('dashboard') }}" class="nav-link {{ $act('dashboard') }}"><i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard</p></a></li>

                    <li class="nav-header">OPERACIÓN</li>
                    <li class="nav-item"><a href="{{ route('movimientos.index') }}" class="nav-link {{ $act('movimientos.*') }}"><i class="nav-icon fas fa-exchange-alt"></i><p>Entradas / Salidas</p></a></li>
                    <li class="nav-item"><a href="{{ route('caja.index') }}" class="nav-link {{ $act('caja.*') }}"><i class="nav-icon fas fa-cash-register"></i><p>Caja</p></a></li>

                    <li class="nav-header">GESTIÓN</li>
                    <li class="nav-item"><a href="{{ route('clientes.index') }}" class="nav-link {{ $act('clientes.*') }}"><i class="nav-icon fas fa-users"></i><p>Clientes / Abonados</p></a></li>
                    <li class="nav-item"><a href="{{ route('vehiculos.index') }}" class="nav-link {{ $act('vehiculos.*') }}"><i class="nav-icon fas fa-car"></i><p>Vehículos</p></a></li>
                    @if($u->esRol('supervisor'))
                        <li class="nav-item"><a href="{{ route('celdas.index') }}" class="nav-link {{ $act('celdas.*') }}"><i class="nav-icon fas fa-th"></i><p>Celdas</p></a></li>
                    @endif
                    @if($u->esRol())
                        <li class="nav-item"><a href="{{ route('pisos.index') }}" class="nav-link {{ $act('pisos.*') }}"><i class="nav-icon fas fa-layer-group"></i><p>Pisos</p></a></li>
                        <li class="nav-item"><a href="{{ route('sectores.index') }}" class="nav-link {{ $act('sectores.*') }}"><i class="nav-icon fas fa-border-all"></i><p>Sectores</p></a></li>
                        <li class="nav-item"><a href="{{ route('tarifas.index') }}" class="nav-link {{ $act('tarifas.*') }}"><i class="nav-icon fas fa-tags"></i><p>Tarifas</p></a></li>
                    @endif

                    @if($u->esRol('supervisor'))
                        <li class="nav-header">REPORTES</li>
                        <li class="nav-item"><a href="{{ route('reportes.index') }}" class="nav-link {{ $act('reportes.*') }}"><i class="nav-icon fas fa-chart-bar"></i><p>Reportes y estadísticas</p></a></li>
                    @endif

                    @if($u->esRol())
                        <li class="nav-header">SEGURIDAD</li>
                        <li class="nav-item"><a href="{{ route('usuarios.index') }}" class="nav-link {{ $act('usuarios.*') }}"><i class="nav-icon fas fa-user-shield"></i><p>Usuarios y roles</p></a></li>
                    @endif
                </ul>
            </nav>
        </div>
    </aside>

    <div class="content-wrapper">
        <section class="content-header"><div class="container-fluid"><h1>@yield('titulo')</h1></div></section>
        <section class="content"><div class="container-fluid">@yield('content')</div></section>
    </div>
    <footer class="main-footer"><strong>ParkControl</strong> · Sistema de estacionamiento</footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    const DT_ES = 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json';

    // Muestra errores de validación / servidor devueltos por AJAX
    function mostrarErrores(xhr) {
        let msg = 'Ocurrió un error inesperado.';
        if (xhr.responseJSON) {
            if (xhr.responseJSON.errors) msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
            else if (xhr.responseJSON.message) msg = xhr.responseJSON.message;
        }
        Swal.fire({ icon: 'error', title: 'No se pudo completar', html: msg });
    }
    const fmtMin = m => (m >= 60 ? Math.floor(m / 60) + ' h ' : '') + (m % 60) + ' min';
    const money = v => '$' + Number(v).toFixed(2);
</script>
@stack('scripts')
</body>
</html>
