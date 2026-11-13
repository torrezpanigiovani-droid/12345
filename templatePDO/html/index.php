<?php
  include "conexion.php"; 
  $pag_actual = "principal";
  if (isset($_SERVER['PATH_INFO'])) {
    $pag_actual = trim($_SERVER['PATH_INFO'], '/');
  }
  $pag_actual = basename($pag_actual);
  $archivoload = $pag_actual . ".php";
  if (empty($pag_actual) || !file_exists(__DIR__ . '/' . $archivoload) || $pag_actual == 'index') {
    $archivoload = "principal.php";
  }
  $base_url = dirname($_SERVER['SCRIPT_NAME']);
  /*INVESTIGACION 
  -la variable super global $_SERVER de php
  -Alternativas a fetch_assoc(),fetch_row(),fetch_array(),fetch_object()
  PRACTICA
  -convertiver a consultas preparadas SQL
  */
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="adminHMD professional admin dashboard template">
  <title>Dashboard | adminHMD</title>
  <link rel="stylesheet" href="<?= $base_url ?>/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= $base_url ?>/assets/vendors/bootstrap-icons/bootstrap-icons.css">
  <link rel="stylesheet" href="<?= $base_url ?>/assets/css/style.css">
</head>
<body>
  <div class="admin-shell">
    <div class="sidebar-backdrop" data-sidebar-close></div>

    <?php include "sidebar.php"; ?>

    <div class="admin-main">
      
      <?php include "navbar.php"; ?>

      <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">
          
          <?php include $archivoload; ?>

        </div>
      </main>
      <?php include "footer.php"; ?>
    </div>
  </div>
  <script src="<?= $base_url ?>/assets/js/bootstrap.bundle.min.js"></script>
  <script src="<?= $base_url ?>/assets/js/main.js"></script>
</body>
</html>
