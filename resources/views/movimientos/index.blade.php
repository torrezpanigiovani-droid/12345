@extends('layouts.app')
@section('titulo', 'Control de entradas y salidas')

@section('content')
<div class="row">
    {{-- ENTRADA --}}
    <div class="col-lg-4">
        <div class="card card-success">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-sign-in-alt"></i> Registrar entrada</h3></div>
            <form id="form-entrada" autocomplete="off">
                <div class="card-body">
                    <div class="form-group">
                        <label>Placa</label>
                        <input type="text" name="placa" class="form-control form-control-lg text-uppercase" maxlength="15" required placeholder="Ej. 1234ABC">
                    </div>
                    <div class="form-group">
                        <label>Tipo de vehículo</label>
                        <select name="tipo" id="e-tipo" class="form-control">
                            <option value="auto">Auto</option><option value="moto">Moto</option><option value="camioneta">Camioneta</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Celda</label>
                        <select name="celda_id" id="e-celda" class="form-control"></select>
                    </div>
                    <a data-toggle="collapse" href="#datos-extra" class="small">+ Datos del vehículo (si es nuevo)</a>
                    <div id="datos-extra" class="collapse mt-2">
                        <input name="marca" class="form-control mb-2" placeholder="Marca">
                        <input name="modelo" class="form-control mb-2" placeholder="Modelo">
                        <input name="color" class="form-control" placeholder="Color">
                    </div>
                </div>
                <div class="card-footer"><button class="btn btn-success btn-block" type="submit"><i class="fas fa-ticket-alt"></i> Registrar y emitir ticket</button></div>
            </form>
        </div>

        <div class="card card-primary">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-sign-out-alt"></i> Registrar salida</h3></div>
            <div class="card-body">
                <label>Ticket o placa</label>
                <div class="input-group">
                    <input type="text" id="s-buscar" class="form-control text-uppercase" placeholder="TCK-... o placa">
                    <div class="input-group-append"><button class="btn btn-primary" id="btn-buscar"><i class="fas fa-search"></i></button></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ACTIVOS --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Vehículos dentro del estacionamiento</h3></div>
            <div class="card-body">
                <table id="tabla" class="table table-bordered table-striped w-100">
                    <thead><tr><th>Ticket</th><th>Placa</th><th>Tipo</th><th>Celda</th><th>Ubicación</th><th>Entrada</th><th>Tiempo</th><th style="width:90px">Acciones</th></tr></thead>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL SALIDA / COBRO --}}
<div class="modal fade" id="modal-salida" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary"><h5 class="modal-title">Salida y cobro</h5><button class="close text-white" data-dismiss="modal">&times;</button></div>
            <div class="modal-body">
                <table class="table table-sm">
                    <tr><th>Ticket</th><td id="m-ticket"></td></tr>
                    <tr><th>Placa</th><td id="m-placa"></td></tr>
                    <tr><th>Celda</th><td id="m-celda"></td></tr>
                    <tr><th>Entrada</th><td id="m-entrada"></td></tr>
                    <tr><th>Tiempo</th><td id="m-tiempo"></td></tr>
                    <tr><th>Tarifa</th><td id="m-tarifa"></td></tr>
                </table>
                <div class="text-center my-2"><span class="text-muted">Total a cobrar</span><h1 id="m-monto" class="text-success mb-0"></h1><small id="m-abonado" class="badge badge-info d-none">Abonado con convenio vigente</small></div>
                <div class="form-row mt-3">
                    <div class="form-group col-6">
                        <label>Método de pago</label>
                        <select id="m-metodo" class="form-control"><option value="efectivo">Efectivo</option><option value="qr">QR</option></select>
                    </div>
                    <div class="form-group col-6"><label>Referencia (opcional)</label><input id="m-ref" class="form-control" maxlength="80"></div>
                </div>
                <div id="qr-box" class="text-center d-none"><div id="qr-pago" class="d-inline-block"></div><div class="small text-muted mt-1">Escanear para pagar</div></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button class="btn btn-success" id="btn-cobrar"><i class="fas fa-check"></i> Confirmar cobro y salida</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
$(function () {
    const U = {
        celdas: @json(route('movimientos.celdas')),
        activos: @json(route('movimientos.activos')),
        entrada: @json(route('movimientos.entrada')),
        calcular: @json(route('movimientos.calcular')),
        salida: @json(route('movimientos.salida', '__ID__')),
    };
    let mov = null;

    const tabla = $('#tabla').DataTable({
        ajax: { url: U.activos, dataSrc: '' },
        order: [[5, 'desc']],
        language: { url: DT_ES },
        columns: [
            { data: 'ticket' }, { data: 'placa' }, { data: 'tipo' }, { data: 'celda' }, { data: 'ubicacion' },
            { data: 'entrada' }, { data: 'minutos', render: m => fmtMin(m) },
            { data: null, orderable: false, render: (d, t, r) =>
                `<button class="btn btn-xs btn-primary btn-salida" data-q="${r.ticket}"><i class="fas fa-sign-out-alt"></i> Salida</button>
                 <a class="btn btn-xs btn-default" target="_blank" href="${@json(url('movimientos'))}/${r.id}/ticket"><i class="fas fa-print"></i></a>` }
        ]
    });
    setInterval(() => tabla.ajax.reload(null, false), 30000);

    function cargarCeldas() {
        $.getJSON(U.celdas, { tipo: $('#e-tipo').val() }, celdas => {
            let h = '<option value="">Asignar automáticamente</option>';
            celdas.forEach(c => h += `<option value="${c.id}">${c.texto}</option>`);
            $('#e-celda').html(h);
        });
    }
    $('#e-tipo').on('change', cargarCeldas);
    cargarCeldas();

    // ----- ENTRADA -----
    $('#form-entrada').on('submit', function (e) {
        e.preventDefault();
        $.post(U.entrada, $(this).serialize()).done(r => {
            this.reset(); cargarCeldas(); tabla.ajax.reload(null, false);
            Swal.fire({
                icon: 'success', title: 'Entrada registrada',
                html: `Ticket <b>${r.ticket}</b><br>Celda asignada: <b>${r.celda}</b>`,
                showCancelButton: true, confirmButtonText: 'Ver / imprimir ticket', cancelButtonText: 'Cerrar'
            }).then(x => { if (x.isConfirmed) window.open(r.url, '_blank'); });
        }).fail(mostrarErrores);
    });

    // ----- SALIDA -----
    function prepararSalida(q) {
        $.getJSON(U.calcular, { q }).done(m => {
            mov = m;
            $('#m-ticket').text(m.ticket); $('#m-placa').text(m.placa); $('#m-celda').text(m.celda);
            $('#m-entrada').text(m.entrada); $('#m-tiempo').text(fmtMin(m.minutos)); $('#m-tarifa').text(m.tarifa);
            $('#m-monto').text(money(m.monto)); $('#m-abonado').toggleClass('d-none', !m.abonado);
            $('#m-metodo').val('efectivo').trigger('change'); $('#m-ref').val('');
            $('#modal-salida').modal('show');
        }).fail(mostrarErrores);
    }
    $('#tabla').on('click', '.btn-salida', function () { prepararSalida($(this).data('q')); });
    $('#btn-buscar').on('click', () => { const q = $('#s-buscar').val().trim(); if (q) prepararSalida(q); });
    $('#s-buscar').on('keypress', e => { if (e.which === 13) $('#btn-buscar').click(); });

    $('#m-metodo').on('change', function () {
        const esQr = $(this).val() === 'qr';
        $('#qr-box').toggleClass('d-none', !esQr);
        $('#qr-pago').empty();
        if (esQr && mov) new QRCode(document.getElementById('qr-pago'), { text: `PAGO|${mov.ticket}|${mov.monto}`, width: 150, height: 150 });
    });

    $('#btn-cobrar').on('click', function () {
        $.post(U.salida.replace('__ID__', mov.id), { metodo: $('#m-metodo').val(), referencia: $('#m-ref').val() }).done(r => {
            $('#modal-salida').modal('hide'); $('#s-buscar').val(''); tabla.ajax.reload(null, false); cargarCeldas();
            Swal.fire({
                icon: 'success', title: 'Salida registrada', html: `Cobrado: <b>${money(r.monto)}</b> (${fmtMin(r.minutos)})`,
                showCancelButton: true, confirmButtonText: 'Imprimir comprobante', cancelButtonText: 'Cerrar'
            }).then(x => { if (x.isConfirmed) window.open(r.url, '_blank'); });
        }).fail(mostrarErrores);
    });
});
</script>
@endpush
