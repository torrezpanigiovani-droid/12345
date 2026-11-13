<?php 
include "conexion.php";

if (isset($_GET['id'])) {
	$id = $_GET['id'];

	$sql = "DELETE FROM personas WHERE idpersona = :id";
	$stmt = $pdo->prepare($sql);
	$values = [
		':id' => $id
	];

	$stmt->execute($values);
}

header("Location:". dirname($_SERVER['SCRIPT_NAME']) . "/index.php/users");
exit();

?>