<div class="container mt-4">
  <div class="card">
    <div class="card-header bg-primary text-white">
      <h4><?= $tituloPagina; ?></h4>
    </div>
    <div class="card-body">
      <form action="<?= BASE_URL ?>/estudiante/actualizar" method="POST">
        <input type="hidden" name="idJugador" value="<?= $estudiante['idJugador']; ?>">
        <div class="mb-3">
          <label class="form-label">Nombre del Jugador</label>
          <input type="text" name="jugador" class="form-control" value="<?= $estudiante['jugador']; ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Posición</label>
          <input type="text" name="posicion" class="form-control" value="<?= $estudiante['posicion']; ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Dorsal</label>
          <input type="number" name="dorsal" class="form-control" value="<?= $estudiante['dorsal']; ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Club</label>
          <input type="text" name="club" class="form-control" value="<?= $estudiante['club']; ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">ID Equipo</label>
          <input type="number" name="equipo_id" class="form-control" value="<?= $estudiante['equipo_id']; ?>" required>
        </div>
        <button type="submit" class="btn btn-success">Actualizar Cambios</button>
        <a href="<?= BASE_URL ?>/estudiante" class="btn btn-secondary">Cancelar</a>
      </form>
    </div>
  </div>
</div>