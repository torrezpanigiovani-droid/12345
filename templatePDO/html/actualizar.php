<?php

include "conexion.php";

if (isset($_GET['id'])) {
	$id = $_GET['id'];
	$sql = "SELECT * FROM personas WHERE idpersona = :id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([':id' => $id]);
	$per = $stmt->fetch();	
}

?>

<form action="<?= dirname($_SERVER['SCRIPT_NAME']) ?>/modificar.php" method="post">
	<input type="hidden" name="idPersona" value="<?= $per['idpersona'] ?>">
  <div class="mb-3 row col-md-12">
    <label for="usuarios" class="col-form-label col-md-3">Nombre</label>
    <div class="">
      <input type="text" class="form-control form-control-sm col-md-7" name="nombre" id="nombre" placeholder="nombre" value="<?= $per['nombre'] ?>">
    </div>
  </div>
  <div class="mb-3">
    <label for="exampleFormControlTextarea1" class="form-label">Ap. Paterno</label>
    <input type="text" class="form-control form-control-sm" name="apPaterno" id="Ap. Paterno" placeholder="Ap. Paterno" value="<?= $per['appaterno'] ?>">
  </div>
  <div class="mb-3">
    <label for="exampleFormControlTextarea1" class="form-label">Ap. Materno</label>
    <input type="text" class="form-control form-control-sm" name="apMaterno" id="Ap. Paterno" placeholder="Ap. Paterno" value="<?= $per['apmaterno'] ?>">
  </div>
  <div class="mb-3">
    <label for="exampleFormControlTextarea1" class="form-label">Edad</label>
    <input type="text" class="form-control form-control-sm" name="edad" id="Edad" placeholder="Introduzca Edad" value="<?= $per['edad'] ?>">
  </div>
  <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
  <button type="submit" class="btn btn-primary" name="modificar">Guardar</button>
</form>