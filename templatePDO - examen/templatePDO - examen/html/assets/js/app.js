function listarUsuarios(){
    $.ajax({
        url: '../listar.php',
        type: 'GET',
        dataType: 'html'
    })
    .then(function(res){
        $('#tablaUsuarios').html(res);
    })
    .catch(function(e){
        console.log("error");
    });
}

$('#formUsuario').on('submit', function (e) {
    e.preventDefault();
    // const formData = $(this).serialize();
    let formData=new FormData($("#formUsuario")[0]);
    $.ajax({
        url: '../guardar.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json'
    })
    .then(function (res) {
        console.log(res);
        // $('#UsuariosModal').hide();
        listarUsuarios();
    })
    .catch(function (error) {
        console.error('Error al guardar:', error);
    });
});