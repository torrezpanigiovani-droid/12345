@extends('productos.layout')

@section('title', 'Productos | Inventario')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">INVENTARIO</p>
            <h1>Productos</h1>
            <p class="muted">Registra, consulta y administra los productos de tu inventario.</p>
        </div>
        <a class="button primary" href="{{ route('productos.create') }}">＋ Nuevo producto</a>
    </div>

    <section class="stats-grid">
        <div class="stat-card"><span>Productos encontrados</span><strong>{{ $productos->total() }}</strong></div>
        <div class="stat-card"><span>Página actual</span><strong>{{ $productos->currentPage() }}</strong></div>
        <div class="stat-card"><span>Por página</span><strong>{{ $productos->perPage() }}</strong></div>
    </section>

    <section class="panel">
        <form class="search-row" method="GET" action="{{ route('productos.index') }}">
            <label class="sr-only" for="buscar">Buscar producto o categoría</label>
            <input id="buscar" type="search" name="buscar" value="{{ $buscar }}" placeholder="Buscar por producto o categoría…">
            <button class="button dark" type="submit">Buscar</button>
            @if ($buscar !== '')
                <a class="button light" href="{{ route('productos.index') }}">Limpiar</a>
            @endif
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>N.º</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($productos as $producto)
                    <tr>
                        <td class="muted">{{ $productos->firstItem() + $loop->index }}</td>
                        <td><strong>{{ $producto->nombre }}</strong><small class="table-sub">{{ $producto->descripcion ?: 'Sin descripción' }}</small></td>
                        <td><span class="category-pill">{{ $producto->categoria }}</span></td>
                        <td class="price">Bs {{ number_format((float) $producto->precio, 2) }}</td>
                        <td>
                            @if ($producto->stock <= 5)
                                <span class="stock low">{{ $producto->stock }} unidades</span>
                            @else
                                <span class="stock">{{ $producto->stock }} unidades</span>
                            @endif
                        </td>
                        <td>
                            <div class="actions">
                                <a class="button small edit" href="{{ route('productos.edit', $producto) }}">Editar</a>
                                <form method="POST" action="{{ route('productos.destroy', $producto) }}" onsubmit="return confirm('¿Seguro que deseas eliminar este producto?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="button small delete" type="submit">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty">
                        <span class="empty-icon">□</span>
                        <strong>No hay productos para mostrar</strong>
                        <p>Registra tu primer producto o prueba otra búsqueda.</p>
                        <a class="button primary" href="{{ route('productos.create') }}">Registrar producto</a>
                    </td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $productos->links() }}</div>
    </section>
@endsection
