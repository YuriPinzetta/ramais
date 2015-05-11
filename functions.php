<?php
function db() {
	$dsn = 'mysql:host=localhost;dbname=ramais';
	$username='yuri'; // Indique o nome do usuÃ¡rio que tem acesso
	$password='Pinzetta'; // Indique a senha do usuÃ¡rio
	
	try{
		$pdo = new PDO($dsn, $username, $password);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	}
	catch(PDOException $ex){
		trigger_error($ex->getTraceAsString());
		throw $ex;
	}
	return $pdo;
}

function inserirContato(array $params, $pdo) {
	$cargo = $params['cargos'];
	$contato = $params['contato'];

	if(empty($cargo) || empty($contato)) {
		return header("HTTP/1.1 404 Bad Request");
	}
	$stmt = $pdo->prepare('INSERT INTO contato(cargos, contato) VALUES (:cargo,:contato)');
	$stmt->execute(array(':cargo' => $cargo, ':contato' => $contato));
}


function inserirRamal($id_contato, $tipos, $ramal, $pdo){
	$id_contato = $_POST['id_contato'];
  $tipos = $_POST['tipos'];
	$ramal = $_POST['ramal'];

	if (empty($tipos) || empty($ramal)) {
		return header("HTTP/1.1 404 Bad Request");
	}
	
	$stmt = $pdo->prepare('select coalesce(max(id),0) +1 as id from ramal');
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$result = $stmt;
	$id_ramal = $result['id'];
	
	$stmt = $pdo->prepare('INSERT INTO ramal(id_contato, id, tipo,ramal) VALUES (:id_contato, :id_ramal, :tipos, :ramal)');
	$stmt->execute(array(':id_contato' => $id_contato, ':id_ramal' => $id_ramal, ':tipos' => $tipos, 'ramal' => $ramal));
}

function listarRamais(array $params, $pdo) {
	$id_contato = $params['id_contato'];
	$tipos = isset($params['tipos']) ? $params['tipos'] : null;
	$stmt = $pdo->prepare("select	id,	ramal, tipo	from ramal where id_contato = '$id_contato'
		" . ($tipos ? "and tipo = '$tipos'" : "") . "
	");
	$ramais = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $ramais;
}

function listarContatos(array $params, $pdo) {
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
	$stmt = $pdo->prepare("
	select id, 
	contato 
	from contato" . (count($filtros) > 0 ? 'where ' . implode(' and ', $filtros) : '') . "
	");
	$stmt->execute();
	$contatos_foreach = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$contatos_return = array();
	foreach ($contatos_foreach as $contato) {
		$params = array(
			'id_contato' => $contato['id'],
			'tipos' => $tipos
		);
		$ramais = listarRamais($params, $pdo);
		$contato['ramais'] = $ramais;
		if ($tipos === null || count($ramais) > 0) {
			$contatos_return[] = $contato;
		}
	}
	return $contatos_return;
}
function listarCargos(array $params, $pdo) {
	$stmt = $pdo->prepare("select distinct cargos from contato");
	$stmt->execute();
	$cargos = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $cargos;
}
function consultaContato(array $params, $pdo){
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
