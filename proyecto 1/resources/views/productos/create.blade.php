@extends('productos.layout')

@section('title', 'Nuevo producto')

@section('content')
    <div class="page-heading">
        <div><p class="eyebrow">INVENTARIO / REGISTRO</p><h1>Nuevo producto</h1><p class="muted">Completa los datos para agregar un producto al inventario.</p></div>
        <a class="button light" href="{{ route('productos.index') }}">← Volver al listado</a>
    </div>

    <section class="panel form-panel">
        <div class="panel-title"><span class="title-icon">＋</span><div><h2>Información del producto</h2><p>Los campos con * son obligatorios.</p></div></div>
        <form method="POST" action="{{ route('productos.store') }}">
            @csrf
            @include('productos._form', ['submitLabel' => 'Guardar producto'])
        </form>
    </section>
@endsection
