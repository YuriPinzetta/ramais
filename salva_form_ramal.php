<?php
	$id_contato = $_POST['id_contato'];
	$tipos = $_POST['tipos'];
	$ramal = $_POST['ramal'];
	
	if (empty($tipos) || empty($ramal)) {
    return header("HTTP/1.1 404 Bad Request");
		  }

	include "../lib/functions.php";

	$conn = db();
	inserirRamal($id_contato, $tipos, $ramal, $conn);
	header('Location: index.php');
?>
