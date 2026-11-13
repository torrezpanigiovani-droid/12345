$(function () {
    const csrf = $('meta[name="csrf-token"]').attr('content');
    const modalElement = document.getElementById('modalProducto');
    const modal = bootstrap.Modal.getOrCreateInstance(modalElement);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json'
        }
    });

    function alerta(titulo, texto, icono = 'success') {
        Swal.fire({ title: titulo, text: texto, icon: icono });
    }

    function escapar(valor) {
        return $('<div>').text(valor ?? '').html();
    }

    function cargarProductos() {
        $.ajax({
            url: '/productos/obtenerProductos',
            method: 'GET',
            dataType: 'json'
        }).done(function (res) {
            const productos = res.productos || [];
            let html = '';

            if (productos.length === 0) {
                html = '<tr><td colspan="6" class="text-center text-secondary py-4">Todavía no hay productos registrados.</td></tr>';
            } else {
                productos.forEach(function (p, indice) {
                    html += `
                        <tr>
                            <td>${indice + 1}</td>
                            <td class="fw-semibold">${escapar(p.producto)}</td>
                            <td>${escapar(p.categoria)}</td>
                            <td>Bs ${Number(p.precio).toFixed(2)}</td>
                            <td>${Number(p.stock)}</td>
                            <td class="text-end text-nowrap">
                                <button type="button" class="btn btn-sm btn-outline-primary editar"
                                    data-id="${p.idproducto}"
                                    data-producto="${escapar(p.producto)}"
                                    data-categoria="${escapar(p.categoria)}"
                                    data-precio="${p.precio}"
                                    data-stock="${p.stock}">Editar</button>
                                <button type="button" class="btn btn-sm btn-outline-danger eliminar"
                                    data-id="${p.idproducto}">Eliminar</button>
                            </td>
                        </tr>`;
                });
            }

            $('.productosTable tbody').html(html);
            $('#contadorProductos').text(productos.length + (productos.length === 1 ? ' producto' : ' productos'));
        }).fail(function () {
            $('.productosTable tbody').html('<tr><td colspan="6" class="text-center text-danger py-4">No se pudieron cargar los productos.</td></tr>');
        });
    }

    function limpiarFormulario() {
        $('#productosForm')[0].reset();
        $('#idproducto').val('');
        $('#modalProductoLabel').text('Añadir producto');
        $('#guardarProducto').text('Guardar producto');
    }

    $('#modalProducto').on('hidden.bs.modal', limpiarFormulario);

    $(document).on('click', '.editar', function () {
        const boton = $(this);
        $('#idproducto').val(boton.data('id'));
        $('#producto').val(boton.attr('data-producto'));
        $('#categoria').val(boton.attr('data-categoria'));
        $('#precio').val(boton.data('precio'));
        $('#stock').val(boton.data('stock'));
        $('#modalProductoLabel').text('Editar producto');
        $('#guardarProducto').text('Guardar cambios');
        modal.show();
    });

    $(document).on('click', '.eliminar', function () {
        const id = $(this).data('id');

        Swal.fire({
            title: '¿Eliminar producto?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(function (result) {
            if (!result.isConfirmed) return;

            $.ajax({
                url: `/productos/${id}`,
                method: 'DELETE'
            }).done(function (res) {
                alerta('Eliminado', res.message || 'Producto eliminado.');
                cargarProductos();
            }).fail(function (xhr) {
                alerta('Error', xhr.responseJSON?.message || 'No se pudo eliminar el producto.', 'error');
            });
        });
    });

    $('#productosForm').on('submit', function (e) {
        e.preventDefault();

        const id = $('#idproducto').val();
        const url = id ? `/productos/${id}` : '/productos';
        const metodo = id ? 'PUT' : 'POST';

        $('#guardarProducto').prop('disabled', true).text('Guardando...');

        $.ajax({
            url: url,
            method: metodo,
            data: $(this).serialize()
        }).done(function (res) {
            modal.hide();
            cargarProductos();
            alerta('Correcto', res.message || 'Producto guardado correctamente.');
        }).fail(function (xhr) {
            let mensaje = xhr.responseJSON?.message || 'Revisa los datos e intenta nuevamente.';
            if (xhr.status === 422 && xhr.responseJSON?.errors) {
                mensaje = Object.values(xhr.responseJSON.errors).flat().join('\n');
            }
            alerta('No se pudo guardar', mensaje, 'error');
        }).always(function () {
            $('#guardarProducto').prop('disabled', false).text(id ? 'Guardar cambios' : 'Guardar producto');
        });
    });

    cargarProductos();
});
