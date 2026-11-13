@extends('layouts.principal')

@section('title', 'Productos')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1">Gestión de productos</h1>
        <p class="text-secondary mb-0">Registra y administra los productos de tu inventario.</p>
    </div>
    <button type="button" class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#modalProducto">
        + Ingresar producto
    </button>
</div>

<div class="card p-3 p-md-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h5 mb-0">Lista de productos</h2>
        <span class="badge text-bg-light" id="contadorProductos">0 productos</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 productosTable">
            <thead>
                <tr>
                    <th>N.º</th>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="6" class="text-center text-secondary py-4">Cargando productos...</td></tr>
            </tbody>
        </table>
    </div>
</div>

@include('productos.formulario')
@endsection

@push('scripts')
<script src="{{ asset('js/productos.js') }}"></script>
@endpush
