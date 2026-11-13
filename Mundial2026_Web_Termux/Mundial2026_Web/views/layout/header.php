<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="adminHMD professional admin dashboard template">
  <title>Dashboard | adminHMD</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/public/vendors/bootstrap-icons/bootstrap-icons.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/public/fontawesome/css/fontawesome.min.css">
</head>
<body>
  <div class="admin-shell">
    <div class="sidebar-backdrop" data-sidebar-close></div>

    <?php require_once "sidebar.php"; ?>

    <div class="admin-main">
      
      <?php require_once "navbar.php"; ?>

      <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">