<?php 

include "conexion.php";
	
if (isset($_POST['modificar'])) {
	$id = $_POST['idPersona'];
	$nombre = $_POST['nombre'];
	$apPaterno = $_POST['apPaterno'];
	$apMaterno = $_POST['apMaterno'];
	$edad = $_POST['edad'];

	$sql = "UPDATE personas SET nombre = :nombre, appaterno = :apPaterno, apmaterno = :apMaterno, edad = :edad WHERE idpersona = :id";

	$stmt = $pdo->prepare($sql);
	$values = [
		':id' => $id,
		':nombre' => $nombre,
		':apPaterno' => $apPaterno,
		':apMaterno' => $apMaterno,
		':edad' => $edad
	];
	$stmt->execute($values);
}

header("Location:". dirname($_SERVER['SCRIPT_NAME']) . "/index.php/users");
exit();

?>