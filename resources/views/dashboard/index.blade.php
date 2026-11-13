@extends('layouts.app')
@section('titulo', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-3 col-6"><div class="small-box bg-info"><div class="inner"><h3 id="k-dentro">–</h3><p>Vehículos dentro</p></div><div class="icon"><i class="fas fa-car"></i></div></div></div>
    <div class="col-md-3 col-6"><div class="small-box bg-success"><div class="inner"><h3 id="k-libres">–</h3><p>Celdas libres</p></div><div class="icon"><i class="fas fa-check"></i></div></div></div>
    <div class="col-md-3 col-6"><div class="small-box bg-danger"><div class="inner"><h3 id="k-ocupadas">–</h3><p>Celdas ocupadas</p></div><div class="icon"><i class="fas fa-ban"></i></div></div></div>
    <div class="col-md-3 col-6"><div class="small-box bg-warning"><div class="inner"><h3 id="k-ingresos">–</h3><p>Ingresos de hoy</p></div><div class="icon"><i class="fas fa-dollar-sign"></i></div></div></div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Mapa de celdas en tiempo real</h3>
        <div class="card-tools"><small class="text-muted mr-2">Se actualiza cada 10 s</small>
            <span class="badge badge-success">Libre</span> <span class="badge badge-danger">Ocupada</span></div>
    </div>
    <div class="card-body" id="mapa"></div>
</div>
@endsection

@push('scripts')
<script>
function cargarStats() {
    $.getJSON(@json(route('dashboard.stats')), s => {
        $('#k-dentro').text(s.dentro); $('#k-libres').text(s.libres);
        $('#k-ocupadas').text(s.ocupadas); $('#k-ingresos').text(money(s.ingresos_hoy));

        const grupos = {};
        s.celdas.forEach(c => { (grupos[c.piso + ' · Sector ' + c.sector] ??= []).push(c); });
        let html = '';
        Object.keys(grupos).sort().forEach(g => {
            html += `<h6 class="mt-2 mb-1">${g}</h6>` + grupos[g].map(c =>
                `<span class="celda ${c.ocupada ? 'ocupada' : 'libre'}">${c.codigo}</span>`).join('');
        });
        $('#mapa').html(html || '<p class="text-muted">No hay celdas configuradas.</p>');
    });
}
cargarStats();
setInterval(cargarStats, 10000);
</script>
@endpush
