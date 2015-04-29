<?php
function listarContatos(array $params, $conn) {
	//$id_contato = $params['id_contato'];
	//$tipos = $params['tipos'];
	$sql = "
		select
			id,
			contato,
			cargo
		from contato
	";
	$res = mysql_query($sql, $conn);
	$result = array();
	while ($row = mysql_fetch_assoc($res)) {
		$result[] = $row;
	}
	return $result;
}
?>
