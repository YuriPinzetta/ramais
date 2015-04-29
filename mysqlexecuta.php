<?php
/*
Esta funÃ§Ã£o executa um comando SQL no banco de dados MySQL
$id - Ponteiro da ConexÃ£o
$sql - ClÃ¡usula SQL a executar
$erro - Especifica se a função exibe ou não(0=não, 1=sim)
$res - Resposta
*/
function mysqlexecuta($id,$sql,$erro = 1) {
	$res = mysql_query($sql,$id);
	if ($res === false) {
		trigger_error(mysql_error($id));
		echo "Ocorreu um erro na execução do Comando SQL no banco de dados. Favor Contactar o Administrador.";
		exit(1);
	}
	return $res;
}
?>
