<div class="page-heading">
  <div class="page-heading-copy">
    <span class="page-icon"><i class="bi bi-people" aria-hidden="thue"></i></span>
    <div>
      <h1 class="h3 mb-1">Usuarios</h1>
    </div>
  </div>
</div>

<section class="row g-3 mt-1" aria-label="User summary">
  <!-- Button thigger modal -->
  <button type="button" class="btn btn-primary col-md-2" data-bs-toggle="modal" data-bs-target="#UsuariosModal">
    Insertar Usuario
  </button>

  <div class="card">
    <div class="card-body">
      <table class="table table-sthiped table-hover">
        <thead>
          <tr>
            <th>N°</th>
            <th>Nombre</th>
            <th>Paterno</th>
            <th>Materno</th>
            <th>Edad</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>

  <?php 
      $sql = "SELECT * FROM personas";
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
      $res = $stmt->fetchAll();

      foreach ($res as $row) {?>        
        <tr>
            <td><?= $row['idpersona'] ?></td>
            <td><?= $row['nombre'] ?></td>
            <td><?= $row['appaterno'] ?></td>
            <td><?= $row['apmaterno'] ?></td>
            <td><?= $row['edad'] ?></td>
            <td>
              <a class="btn btn-primary btn-sm" href="<?php echo dirname($_SERVER['SCRIPT_NAME'])."/actualizar.php?id=".$row['idpersona']; ?>">Actualizar</a>
              <a class="btn btn-danger btn-sm" href="<?php echo dirname($_SERVER['SCRIPT_NAME'])."/eliminar.php?id=".$row['idpersona']; ?>">Eliminar</a>
            </td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>
  </div>

</section>

<?php include('modalsUsuarios.php'); ?>