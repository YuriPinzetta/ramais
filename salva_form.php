<?php
	$cargo = $_POST['cargos'];
	$contato = $_POST['contato'];

	if (empty($cargo) || empty($contato)) {
		return header("HTTP/1.1 404 Bad Request");
	}

	include "./mysqlconecta.php"; // Conecta ao banco de dados
	include "./mysqlexecuta.php"; // Executa a clausula SQL
	
	//$sql="INSERT INTO 'agenda_online' (local, titulo) VALUES ('".$local."', '".$titulo."')";
	//$finalizar = mysql_query($sql);

	$sql = "INSERT INTO `ramais`.`contato` (`cargos`, `contato`) 
							VALUES ('$cargo', '$contato');";
	$res = mysqlexecuta($id,$sql);
	header('Location: index.php');
?>
