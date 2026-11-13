@extends('layouts.app')
@section('titulo', 'Caja y facturación')

@section('content')
<div class="row">
    <div class="col-md-3 col-6"><div class="small-box bg-success"><div class="inner"><h3 id="t-total">$0.00</h3><p>Total cobrado</p></div><div class="icon"><i class="fas fa-coins"></i></div></div></div>
    <div class="col-md-3 col-6"><div class="small-box bg-info"><div class="inner"><h3 id="t-efectivo">$0.00</h3><p>Efectivo</p></div><div class="icon"><i class="fas fa-money-bill-wave"></i></div></div></div>
    <div class="col-md-3 col-6"><div class="small-box bg-primary"><div class="inner"><h3 id="t-qr">$0.00</h3><p>QR</p></div><div class="icon"><i class="fas fa-qrcode"></i></div></div></div>
    <div class="col-md-3 col-6"><div class="small-box bg-secondary"><div class="inner"><h3 id="t-trx">0</h3><p>Transacciones</p></div><div class="icon"><i class="fas fa-receipt"></i></div></div></div>
</div>

<div class="card">
    <div class="card-header">
        <form class="form-inline" id="filtros">
            <label class="mr-2">Desde</label><input type="date" name="desde" class="form-control form-control-sm mr-3" value="{{ today()->toDateString() }}">
            <label class="mr-2">Hasta</label><input type="date" name="hasta" class="form-control form-control-sm" value="{{ today()->toDateString() }}">
        </form>
    </div>
    <div class="card-body">
        <table id="tabla" class="table table-bordered table-striped w-100">
            <thead><tr><th>Fecha</th><th>Ticket</th><th>Placa</th><th>Min.</th><th>Método</th><th>Referencia</th><th>Cajero</th><th>Monto</th></tr></thead>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    const tabla = $('#tabla').DataTable({
        ajax: {
            url: @json(route('caja.data')),
            data: d => { $('#filtros').serializeArray().forEach(f => d[f.name] = f.value); },
            dataSrc: json => {
                $('#t-total').text(money(json.totales.total)); $('#t-efectivo').text(money(json.totales.efectivo));
                $('#t-qr').text(money(json.totales.qr)); $('#t-trx').text(json.totales.transacciones);
                return json.filas;
            }
        },
        language: { url: DT_ES },
        order: [[0, 'desc']],
        columns: [
            { data: 'fecha' }, { data: 'ticket' }, { data: 'placa' }, { data: 'minutos' },
            { data: 'metodo', render: m => m === 'qr' ? '<span class="badge badge-primary">QR</span>' : '<span class="badge badge-info">Efectivo</span>' },
            { data: 'referencia', defaultContent: '—', render: v => v || '—' },
            { data: 'cajero' }, { data: 'monto', render: v => money(v) }
        ]
    });
    $('#filtros input').on('change', () => tabla.ajax.reload());   // filtro dinámico sin recargar
});
</script>
@endpush
