@extends('layouts.app')
@section('titulo', $titulo)

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center">
        <h3 class="card-title mb-0">Listado · {{ $titulo }}</h3>
        <button class="btn btn-primary btn-sm ml-auto" id="btn-nuevo"><i class="fas fa-plus"></i> Nuevo</button>
    </div>
    <div class="card-body">
        <table id="tabla" class="table table-bordered table-striped w-100">
            <thead>
            <tr>
                @foreach($columnas as $c)<th>{{ $c['label'] }}</th>@endforeach
                <th>Estado</th>
                <th style="width:110px">Acciones</th>
            </tr>
            </thead>
        </table>
    </div>
</div>

<div class="modal fade" id="modal-form" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="form" class="modal-content" autocomplete="off">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-titulo"></h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    @foreach($campos as $c)
                        <div class="form-group col-md-{{ $c['col'] ?? 6 }}">
                            <label for="f-{{ $c['name'] }}">{{ $c['label'] }}</label>
                            @if($c['type'] === 'select')
                                <select class="form-control" id="f-{{ $c['name'] }}" name="{{ $c['name'] }}">
                                    @foreach($c['options'] as $o)<option value="{{ $o['v'] }}">{{ $o['t'] }}</option>@endforeach
                                </select>
                            @else
                                <input class="form-control" type="{{ $c['type'] }}" id="f-{{ $c['name'] }}" name="{{ $c['name'] }}"
                                       @if(isset($c['step'])) step="{{ $c['step'] }}" @endif>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
            </div>
        </form> 
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    const campos = @json($campos);
    const columnas = @json($columnas);
    const URL_DATA = @json(route($ruta . '.data'));
    const URL_STORE = @json(route($ruta . '.store'));
    const URL_UPDATE = @json(route($ruta . '.update', '__ID__'));
    const URL_TOGGLE = @json(route($ruta . '.toggle', '__ID__'));
    let editId = null;

    const cols = columnas.map(c => ({
        data: c.data,
        defaultContent: '—',
        render: v => {
            if (v === null || v === undefined || v === '') return '—';
            if (c.type === 'ocupada') return v ? '<span class="badge badge-danger">Ocupada</span>' : '<span class="badge badge-success">Libre</span>';
            if (c.type === 'money') return money(v);
            return $('<div>').text(v).html();
        }
    }));
    cols.push({ data: 'estado', render: v => v ? '<span class="badge badge-success">Activo</span>' : '<span class="badge badge-secondary">Inactivo</span>' });
    cols.push({
        data: null, orderable: false, searchable: false,
        render: (d, t, row) => `
            <button class="btn btn-xs btn-info btn-editar" title="Editar"><i class="fas fa-edit"></i></button>
            <button class="btn btn-xs ${row.estado ? 'btn-warning' : 'btn-success'} btn-estado" title="${row.estado ? 'Desactivar' : 'Activar'}">
                <i class="fas fa-power-off"></i></button>`
    });

    const tabla = $('#tabla').DataTable({
        ajax: { url: URL_DATA, dataSrc: '' },
        columns: cols,
        order: [],
        language: { url: DT_ES },
        responsive: true
    });

    $('#btn-nuevo').on('click', function () {
        editId = null;
        $('#form')[0].reset();
        $('#modal-titulo').text('Nuevo registro');
        $('#modal-form').modal('show');
    });

    $('#tabla').on('click', '.btn-editar', function () {
        const row = tabla.row($(this).closest('tr')).data();
        editId = row.id;
        $('#form')[0].reset();
        campos.forEach(c => {
            let v = c.type === 'password' ? '' : (row[c.name] ?? '');
            $('#f-' + c.name).val(String(v));
        });
        $('#modal-titulo').text('Editar registro #' + row.id);
        $('#modal-form').modal('show');
    });

    $('#form').on('submit', function (e) {
        e.preventDefault();
        let data = $(this).serialize();
        if (editId) data += '&_method=PUT';
        $.ajax({ url: editId ? URL_UPDATE.replace('__ID__', editId) : URL_STORE, method: 'POST', data })
            .done(r => {
                $('#modal-form').modal('hide');
                Swal.fire({ icon: 'success', title: r.message, timer: 1400, showConfirmButton: false });
                tabla.ajax.reload(null, false);
            })
            .fail(mostrarErrores);
    });

    $('#tabla').on('click', '.btn-estado', function () {
        const row = tabla.row($(this).closest('tr')).data();
        Swal.fire({
            title: row.estado ? '¿Desactivar registro?' : '¿Activar registro?',
            text: 'El registro no se elimina; solo cambia su estado.',
            icon: 'question', showCancelButton: true, confirmButtonText: 'Sí', cancelButtonText: 'Cancelar'
        }).then(res => {
            if (!res.isConfirmed) return;
            $.ajax({ url: URL_TOGGLE.replace('__ID__', row.id), method: 'POST', data: { _method: 'PATCH' } })
                .done(r => { Swal.fire({ icon: 'success', title: r.message, timer: 1200, showConfirmButton: false }); tabla.ajax.reload(null, false); })
                .fail(mostrarErrores);
        });
    });
});
</script>
@endpush
