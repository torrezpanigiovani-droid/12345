@extends('layouts.app')
@section('titulo', 'Reportes y estadísticas')

@section('content')
<div class="card">
    <div class="card-body">
        <form id="filtros" class="form-row align-items-end">
            <div class="form-group col-md-3">
                <label>Reporte</label>
                <select name="tipo" class="form-control">
                    <option value="ingresos_dia">Ingresos por día</option>
                    <option value="ingresos_mes">Ingresos por mes</option>
                    <option value="frecuentes">Vehículos más frecuentes</option>
                    <option value="ocupacion">Ocupación histórica</option>
                </select>
            </div>
            <div class="form-group col-md-2"><label>Desde</label><input type="date" name="desde" class="form-control" value="{{ now()->startOfMonth()->toDateString() }}"></div>
            <div class="form-group col-md-2"><label>Hasta</label><input type="date" name="hasta" class="form-control" value="{{ today()->toDateString() }}"></div>
            <div class="form-group col-md-5 text-md-right">
                <button type="button" class="btn btn-danger" id="btn-pdf"><i class="fas fa-file-pdf"></i> Exportar PDF</button>
                <button type="button" class="btn btn-success" id="btn-excel"><i class="fas fa-file-excel"></i> Exportar Excel</button>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-lg-5"><div class="card"><div class="card-header"><h3 class="card-title" id="r-titulo">Gráfico</h3></div><div class="card-body"><canvas id="grafico" height="220"></canvas></div></div></div>
    <div class="col-lg-7"><div class="card"><div class="card-header"><h3 class="card-title" id="r-subtitulo">Detalle</h3></div><div class="card-body"><table id="tabla" class="table table-bordered table-striped w-100"></table></div></div></div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
$(function () {
    const U = { datos: @json(route('reportes.datos')), pdf: @json(route('reportes.pdf')), excel: @json(route('reportes.excel')) };
    let tabla = null, chart = null;

    // Filtros dinámicos con AJAX: sin recargar la página
    function cargar() {
        $.getJSON(U.datos, $('#filtros').serialize()).done(r => {
            $('#r-titulo').text(r.titulo); $('#r-subtitulo').text(r.titulo + ' · ' + r.periodo);

            if (tabla) { tabla.destroy(); $('#tabla').empty(); }
            tabla = $('#tabla').DataTable({
                data: r.filas, columns: r.columnas.map(t => ({ title: t })),
                language: { url: DT_ES }, order: []
            });

            if (chart) chart.destroy();
            chart = new Chart(document.getElementById('grafico'), {
                type: r.tipo === 'frecuentes' ? 'bar' : 'line',
                data: { labels: r.filas.map(f => f[0]), datasets: [{
                    label: r.grafico.titulo, data: r.filas.map(f => Number(f[r.grafico.valor])),
                    backgroundColor: 'rgba(0,123,255,.5)', borderColor: '#007bff', tension: .25, fill: true
                }] },
                options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
            });
        }).fail(mostrarErrores);
    }

    $('#filtros select, #filtros input').on('change', cargar);
    $('#btn-pdf').on('click', () => window.location = U.pdf + '?' + $('#filtros').serialize());
    $('#btn-excel').on('click', () => window.location = U.excel + '?' + $('#filtros').serialize());
    cargar();
});
</script>
@endpush
