<?php

$host    = 'localhost';
$db      = 'db_asistencia';
$user    = 'postgres';
$password = 'Pandadario#1';
$port    = '5786';

$dsn = "pgsql:host=$host;port=$port;dbname=$db;";

try {
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $pdo = new PDO($dsn, $user, $password, $options);

} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}

?>

