<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gestión de productos')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6fb; color: #202938; }
        .navbar-brand { font-weight: 700; letter-spacing: .2px; }
        .page-wrap { max-width: 1100px; }
        .card { border: 0; border-radius: 16px; box-shadow: 0 8px 28px rgba(31,45,61,.07); }
        .table thead th { color: #64748b; font-size: .82rem; text-transform: uppercase; letter-spacing: .04em; }
        .btn { border-radius: 9px; }
        .modal-content { border: 0; border-radius: 16px; }
    </style>
    @stack('styles')
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
    <div class="container page-wrap">
        <a class="navbar-brand" href="{{ route('productos.index') }}">Inventario · Productos</a>
    </div>
</nav>
<main class="container page-wrap py-4">
    @yield('content')
</main>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@stack('scripts')
</body>
</html>
