<?php

$fd = fopen('contatos.txt', 'r');
if ($fd === false) {
	exit(1);
}
/*
array(
	array('nome' => 'Yuri', 'ramal' => '123'),
	array('nome' => 'Yuri', 'ramal' => '123'),
	array('nome' => 'Yuri', 'ramal' => '123'),
	array('nome' => 'Yuri', 'ramal' => '123')
)
*/
/*$linha1 = fgets($fd);
var_dump($linha1);
$colunas = explode("\t", $linha1);
$nome = $colunas[0];
$ramal = rtrim($colunas[1]);
var_dump($nome);
var_dump($ramal);
//"Yuri\t123\n"
*/
/*
$linha= fgets($fd);
$coluna = explode("\t", $linha);
foreach($coluna as $colunas){
	$coluna[] = $colunas;
}*/
while(feof($fd) === false) {
	$linha = fgets($fd);
	if ($linha !== false) {
		//$column = array('nome', 'ramal');	
		//$column = array('nome' => 'Yuri', 'ramal' => 123);
		var_dump($linha);
	}
}
?>
