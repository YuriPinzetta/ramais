<?php
	$id_contato = $_POST['id_contato'];
	$tipos = $_POST['tipos'];
	$ramal = $_POST['ramal'];

	include "./mysqlconecta.php"; // Conecta ao banco de dados
	include "./mysqlexecuta.php"; // Executa a clausula SQL
	
	$sql = "select coalesce(max(id),0) + 1 as id from ramal";
	$res = mysql_query($sql, $id);
	$result = mysql_fetch_assoc($res);
	$id_ramal = $result['id'];

	$sql = "INSERT INTO ramal (id_contato, id, tipo, ramal) 
							VALUES ('$id_contato', '$id_ramal', '$tipos', '$ramal');";
	$res = mysqlexecuta($id,$sql);
	var_dump($res);
	header('Location: index.php');
?>
