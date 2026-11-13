@extends('productos.layout')

@section('title', 'Editar producto')

@section('content')
    <div class="page-heading">
        <div><p class="eyebrow">INVENTARIO / EDICIÓN</p><h1>Editar producto</h1><p class="muted">Actualiza la información de {{ $producto->nombre }}.</p></div>
        <a class="button light" href="{{ route('productos.index') }}">← Volver al listado</a>
    </div>

    <section class="panel form-panel">
        <div class="panel-title"><span class="title-icon">✎</span><div><h2>Información del producto</h2><p>Modifica los campos necesarios y guarda los cambios.</p></div></div>
        <form method="POST" action="{{ route('productos.update', $producto) }}">
            @csrf
            @method('PUT')
            @include('productos._form', ['submitLabel' => 'Guardar cambios'])
        </form>
    </section>
@endsection
