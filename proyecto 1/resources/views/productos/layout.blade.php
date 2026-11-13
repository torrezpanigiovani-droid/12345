<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Inventario de productos')</title>
    <link rel="stylesheet" href="{{ asset('css/productos.css') }}">
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <a class="brand" href="{{ route('productos.index') }}">
                <span class="brand-icon">P</span>
                <span>Panel de productos<small>Inventario y control de stock</small></span>
            </a>
            <a class="top-link" href="{{ route('productos.index') }}">Productos</a>
        </div>
    </header>

    <main class="container">
        @if (session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert error">
                <strong>Revisa los siguientes datos:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="footer">Sistema de gestión de productos · Laravel</footer>
</body>
</html>
