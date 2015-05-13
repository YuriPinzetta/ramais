<?php
	$cargo = $_POST['cargos'];
	$contato = $_POST['contato'];

	if (empty($cargo) || empty($contato)) {
		return header("HTTP/1.1 404 Bad Request");
	}

	include "../lib/functions.php";

	$conn = db();
	inserirContato($cargo, $contato, $conn);
	header('Location: index.php');
?>
