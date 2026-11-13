<?php 
include "conexion.php";
	
if (isset($_POST['guardar'])) {
	$nombre = $_POST['nombre'];
	$apPaterno = $_POST['apPaterno'];
	$apMaterno = $_POST['apMaterno'];
	$edad = $_POST['edad'];

	$sql = "INSERT INTO personas(nombre,appaterno,apmaterno,edad)values(:nombre,:apPaterno,:apMaterno,:edad)";
	$stmt = $pdo->prepare($sql);
	$values = [
		':nombre' => $nombre,
		':apPaterno' => $apPaterno,
		':apMaterno' => $apMaterno,
		':edad' => $edad
	];
	$stmt->execute($values);

}
header("Location:". dirname($_SERVER['SCRIPT_NAME']) . "/index.php/users");
exit();

"SELECT * FROM usuarios WHERE user = ' ' OR 1=1 --' AND pass = '***'"

?>