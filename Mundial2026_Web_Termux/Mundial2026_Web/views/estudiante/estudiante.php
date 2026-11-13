<div class="page-heading">
  <div class="page-heading-copy">
    <span class="page-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
    <div>
      <h1 class="h3 mb-1"><?= $tituloPagina; ?></h1>
    </div>
  </div>
</div>

<section class="row g-3 mt-1" aria-label="User summary">
  <button type="button" class="btn btn-primary col-md-2 mb-2" data-bs-toggle="modal" data-bs-target="#UsuariosModal">
    Insertar Jugador
  </button>

  <div class="card">
    <div class="card-body">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>N°</th>
            <th>Nombre</th>
            <th>Posición</th>
            <th>Dorsal</th>
            <th>Club</th>
            <th>Equipo</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
        



        
        </tbody>
      </table>
    </div>
  </div>
</section>


<div class="modal fade" id="UsuariosModal" tabindex="-1" aria-labelledby="UsuariosModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="<?= BASE_URL ?>/estudiante/guardar" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="UsuariosModalLabel">Registrar Jugador</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nombre del Jugador</label>
            <input type="text" name="jugador" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Posición</label>
            <input type="text" name="posicion" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Dorsal</label>
            <input type="number" name="dorsal" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Club</label>
            <input type="text" name="club" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">ID Equipo</label>
            <input type="number" name="equipo_id" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>