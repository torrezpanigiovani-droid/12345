<!-- Modal -->
<div class="modal fade" id="UsuariosModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Insertar Un usuario</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="<?= dirname($_SERVER['SCRIPT_NAME']) ?>/guardar.php" method="post">
          <div class="mb-3 row col-md-12">
            <label for="usuarios" class="col-form-label col-md-3">Nombre</label>
            <div class="">
              <input type="text" class="form-control form-control-sm col-md-7" name="nombre" id="nombre" placeholder="nombre">
            </div>
          </div>
          <div class="mb-3">
            <label for="exampleFormControlTextarea1" class="form-label">Ap. Paterno</label>
            <input type="text" class="form-control form-control-sm" name="apPaterno" id="Ap. Paterno" placeholder="Ap. Paterno">
          </div>
          <div class="mb-3">
            <label for="exampleFormControlTextarea1" class="form-label">Ap. Materno</label>
            <input type="text" class="form-control form-control-sm" name="apMaterno" id="Ap. Paterno" placeholder="Ap. Paterno">
          </div>
          <div class="mb-3">
            <label for="exampleFormControlTextarea1" class="form-label">Edad</label>
            <input type="text" class="form-control form-control-sm" name="edad" id="Edad" placeholder="Introduzca Edad">
          </div>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary" name="guardar">Guardar</button>
        </form>
      </div>
      <div class="modal-footer">
        
      </div>
    </div>
  </div>
</div>