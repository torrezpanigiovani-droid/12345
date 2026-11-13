<?php


$host    = 'localhost';
$db      = 'templeidPDO';
$user    = 'postgres';
$password = 'postgres';
$port    = '5432';

// Cadena de conexión (DSN)
$dsn = "pgsql:host=$host;port=$port;dbname=$db;";

try {
    // Crear la instancia de PDO con opciones de seguridad
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Reportar errores como excepciones
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Traer los datos como arrays asociativos
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Usar consultas preparadas nativas
    ];
    
    $pdo = new PDO($dsn, $user, $password, $options);

} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}




//conexion mysqli

/*$con = new mysqli("localhost",'admin',"Pandadario1#","usuarios");

if($con->connect_error) die("Error en la conexion" . $con->connect_error);
*/
?>