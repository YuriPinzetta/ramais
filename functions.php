<?php
function db() {
	/* Este arquivo conecta um banco de dados MySQL - Servidor = localhost*/
	$dbname="ramais"; // Indique o nome do banco de dados que serÃ¡ aberto
	$usuario="yuri"; // Indique o nome do usuÃ¡rio que tem acesso
	$password="Pinzetta"; // Indique a senha do usuÃ¡rio
	//1Âº passo - Conecta ao servidor MySQL

	//mysql_connect("localhost",$usuario,$password);

	if(!($id = mysql_connect("localhost",$usuario,$password))) {
		echo "Não foi possível estabelecer uma conexão com o gerenciador MySQL. Favor Contactar o Administrador.";
		exit;
	}

	//2Âº passo - Seleciona o Banco de Dados
	if(!($con=mysql_select_db($dbname,$id))) {
		echo "Não foi possível estabelecer uma conexão com o gerenciador MySQL. Favor Contactar o Administrador.";
		exit;
	}
	return $id;
}

/*
Esta funÃ§Ã£o executa um comando SQL no banco de dados MySQL
$conn - Ponteiro da ConexÃ£o
$sql - ClÃ¡usula SQL a executar
$erro - Especifica se a função exibe ou não(0=não, 1=sim)
$res - Resposta
*/
function dbExecuta($conn,$sql,$erro = 1) {
	$res = mysql_query($sql,$conn);
	if ($res === false) {
		trigger_error(mysql_error($conn));
		echo "Ocorreu um erro na execução do Comando SQL no banco de dados. Favor Contactar o Administrador.";
		exit(1);
	}
	return $res;
}

function inserirContato($cargo, $contato, $conn) {
	$sql = "INSERT INTO `ramais`.`contato` (`cargos`, `contato`) 
							VALUES ('$cargo', '$contato');";
	return dbExecuta($conn, $sql);
}


function inserirRamal($id_contato, $tipos, $ramal, $conn){
	$sql = "select coalesce(max(id),0) + 1 as id from ramal";
	$res = mysql_query($sql, $conn);
	$result = mysql_fetch_assoc($res);
	$id_ramal = $result['id'];

		$sql = "INSERT INTO ramal (id_contato, id, tipo, ramal) 
								VALUES ('$id_contato', '$id_ramal', '$tipos', '$ramal');";
		return dbExecuta($conn,$sql);
	}

	function listarRamais(array $params, $conn) {
		$id_contato = $params['id_contato'];
		$tipos = isset($params['tipos']) ? $params['tipos'] : null;
		$sql = "
			select
				id,
				ramal,
				tipo
			from ramal
			where id_contato = '$id_contato'
			" . ($tipos ? "and tipo = '$tipos'" : "") . "
		";
		$ramais = array();
		$res = mysql_query($sql, $conn);
		if ($res === false) {
			trigger_error(mysql_error($conn));
			return $ramais;
		}
		while ($ramal = mysql_fetch_assoc($res)) {
			$ramais[] = $ramal;
		}
		return $ramais;
	}

	function listarContatos(array $params, $conn) {
		$id_contato = !empty($params['id_contato']) ? $params['id_contato'] : null;
		$tipos = !empty($params['tipos']) ? $params['tipos'] : null;
		$cargos = !empty($params['cargos']) ? $params['cargos'] : null;
		$filtros = array();
		if ($id_contato !== null) {
			$filtros[] = "id = $id_contato";
		}
		if ($cargos !== null) {
			$filtros[] = "cargos = '$cargos'";
		}
		$sql = "
			select
				id,
				contato,
				cargos
			from contato
			" . (count($filtros) > 0 ? 'where ' . implode(' and ', $filtros) : '') . "
		";
		$contatos = array();
		$res = mysql_query($sql, $conn);
		if ($res === false) {
			trigger_error(mysql_error($conn));
			return $contatos;
		}
		while ($contato = mysql_fetch_assoc($res)) {
			$params = array(
				'id_contato' => $contato['id'],
				'tipos' => $tipos
			);
			$ramais = listarRamais($params, $conn);
			$contato['ramais'] = $ramais;
			if ($tipos === null || count($ramais) > 0) {
				$contatos[] = $contato;
			}
		}
		return $contatos;
	}
	function listarCargos(array $params, $conn) {
		$sql = "
			select
				distinct cargos
			from contato
		";
		$cargos = array();
		$res = mysql_query($sql, $conn);
		if ($res === false) {
			trigger_error(mysql_error($conn));
			return $cargos;
		}
		while ($cargo = mysql_fetch_assoc($res)) {
			$cargos[] = $cargo;
		}
		return $cargos;
	}
	function consultaContato(array $params, $conn){
		$id = $params['id_contato'];
		$sql = "select id,contato,cargos from contato WHERE id = '$id'";
		$res = mysql_query($sql, $conn);
		if($res === false){
			trigger_error(mysql_error($conn));
			return false;
		}
		$contato = mysql_fetch_assoc($res);
		return $contato;
	}
	function alteraContato(array $params, array $ramais, $conn){
		$id = $params['id_contato'];
		$contatos = $params['contato'];
		$cargos = $params['cargos'];
		$sql = "update contato set contato='$contatos', cargos='$cargos' WHERE id = '$id'";
		$res = mysql_query($sql, $conn);
		if($res === false){
			trigger_error(mysql_error($conn));
			return false;
		}
		alteraRamais($id, $ramais, $conn);
	}
	function alteraRamais($id_contato, array $ramais, $conn) {
		foreach ($ramais as $ramal) {
			alteraRamal($id_contato, $ramal, $conn);
		}
	}
	function alteraRamal($id_contato, array $params, $conn) {
		$id = $params['id'];
		$tipos = $params['tipos'];
		$ramal = $params['ramal'];
		$sql = "update ramal set tipo='$tipos', ramal='$ramal' WHERE id_contato = '$id_contato' and id = '$id'";
		$res = mysql_query($sql, $conn);
		if($res === false){
			trigger_error(mysql_error($conn));
			return false;
		}
	}
	function deletaRamal(array $params, $conn) {
		$id = $params['id_contato'];
		$sql = "delete from ramal WHERE id_contato = '$id'";
		$res = mysql_query($sql, $conn);
		if($res === false){
			trigger_error(mysql_error($conn));
			return false;
		}
		return true;
	}
	function deletaContato(array $params, $conn) {
		$id = $params['id_contato'];
		$sql = "delete from contato WHERE id = '$id'";
		$res = mysql_query($sql, $conn);
		if($res === false){
			trigger_error(mysql_error($conn));
			return false;
		}
		return true;
	}
	function validaUsuario(array $params, $conn){
		$login = $params['usuario'];
		$senha = md5($params['senha'].".AMIX");
		$sql  = "select login from usuario where login = '$login' and senha = '$senha' ";
		$res = mysql_query($sql, $conn);
		//var_dump($res);
		$nlinha = mysql_num_rows($res);
		//var_dump($nlinha);
		if($nlinha > 0){
			$usuario = mysql_fetch_assoc($res);
			$_SESSION['usuario'] = $usuario['login'];
			header ("Location: index.php");
		} else{
			echo "<script>alert ('Usuario ou senha não existe.')</script>";
		}
		//var_dump($params);
	}
	function usuarioLogado(){
		if(array_key_exists('usuario', $_SESSION) && $_SESSION['usuario'] != ""){
			return true;
		} else{
			return false;
		}
	}
	function cadastraUsuario(array $params, $conn){
		$ilogin = $params['usuario'];
		$isenha = md5($params['senha'].".AMIX");
		$sql = "INSERT INTO `ramais`.`usuario` (`login`, `senha`) 
								VALUES ('$ilogin', '$isenha');";
		return dbExecuta($conn, $sql);
	}
	function verificaUsuario(array $params, $conn){
		$login = $params['usuario'];
		$sql = "select login from usuario where login = '$login'";
		$res = mysql_query($sql, $conn);
		$existe = mysql_num_rows($res);
	if ($existe == 0){
		cadastraUsuario($_POST, $conn);
		return header ("Location: login.php");
	} else{
		echo "<script>alert ('Este usuário já existe, tente outro.')</script>";
	}
}
?>
