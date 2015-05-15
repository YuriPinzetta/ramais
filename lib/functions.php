<?php
function db()
{
    $dsn = 'mysql:host=localhost;dbname=ramais';
    $username='yuri'; // Indique o nome do usuÃ¡rio que tem acesso
    $password='Pinzetta'; // Indique a senha do usuÃ¡rio
    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    } catch (PDOException $ex) {
        trigger_error($ex->getTraceAsString());
        throw $ex;
    }
    return $pdo;
}

function inserirContato(array $params, $pdo)
{
    $cargo = $params['cargos'];
    $contato = $params['contato'];

    if (empty($cargo) || empty($contato)) {
        return header("HTTP/1.1 404 Bad Request");
    }
    $stmt = $pdo->prepare('INSERT INTO contato(cargos, contato) VALUES (:cargo,:contato)');
    $stmt->execute(array(':cargo' => $cargo, ':contato' => $contato));
    header("Location: index.php");
}


function inserirRamal(array $params, $pdo)
{
    $id_contato = $params['id_contato'];
    $tipos = $params['tipos'];
    $ramal = $params['ramal'];

    if (empty($tipos) || empty($ramal)) {
        return header("HTTP/1.1 404 Bad Request");
    }
    
    $stmt = $pdo->prepare('select coalesce(max(id),0) + 1 as id from ramal');
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $id_ramal = $result['id'];
    $stmt = $pdo->prepare('INSERT INTO ramal(id_contato, id, tipo,ramal) 
       VALUES (:id_contato, :id_ramal, :tipos, :ramal)');
    $stmt->execute(array(':id_contato' => $id_contato,
       ':id_ramal' => $id_ramal,
       ':tipos' => $tipos, 'ramal' => $ramal));
    header("Location: index.php");
}

function listarRamais(array $params, $pdo)
{
    $id_contato = $params['id_contato'];
    $tipos = isset($params['tipos']) ? $params['tipos'] : null;
    $stmt = $pdo->prepare("select   id, ramal, tipo from ramal where id_contato = '$id_contato'
        " . ($tipos ? "and tipo = '$tipos'" : "") . "
    ");
    $stmt->execute(array(':id_contato' => $id_contato, ':tipos' => $tipos));
    $ramais = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $ramais;
}

function listarContatos(array $params, $pdo)
{
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
    cargos,
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
function listarCargos(array $params, $pdo)
{
    $stmt = $pdo->prepare("select distinct cargos from contato");
    $stmt->execute();
    $cargos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $cargos;
}
function consultaContato(array $params, $pdo)
{
    $id = $params['id_contato'];
    $stmt = $pdo->prepare('select id, contato, cargos from contato WHERE id = :id');
    $stmt->execute(array(':id' => $id));
    $contato = $stmt->fetch(PDO::FETCH_ASSOC);
    return $contato;
}
function alteraContato(array $params, array $ramais, $pdo)
{
    $contatos = $params['contato'];
    $cargos = $params['cargos'];
    $id = $params['id_contato'];
    $stmt = $pdo->prepare("update contato set contato=:contatos, cargos=:cargos WHERE id = :id");
    $stmt->execute(array(':contatos' => $contatos,':cargos' => $cargos,':id' => $id));
    alteraRamais($id, $ramais, $pdo);
}
function alteraRamais($id_contato, array $ramais, $pdo)
{
    foreach ($ramais as $ramal) {
        alteraRamal($id_contato, $ramal, $pdo);
    }
}
function alteraRamal($id_contato, array $params, $pdo)
{
    $tipos = $params['tipos'];
    $ramal = $params['ramal'];
    $id = $params['id'];
    $stmt = $pdo->prepare("update ramal set tipo = :tipos, ramal = :ramal WHERE id_contato = :id_contato and id = :id");
    $stmt->execute(array(':tipos' => $tipos,':ramal' => $ramal,':id_contato' => $id_contato, ':id' => $id));
    header("Location: index.php");
}
function deletaRamal(array $params, $pdo)
{
    $id = $params['id_contato'];
    $stmt = $pdo->prepare("delete from ramal WHERE id_contato = :id");
    $stmt->execute(array(':id' => $id));
    header("Location: index.php");
}
function deletaContato(array $params, $pdo)
{
    $id = $params['id_contato'];
    $stmt = $pdo->prepare("delete from contato WHERE id = :id");
    $stmt->execute(array(':id' => $id));
    header("Location: index.php");
}
function validaUsuario(array $params, $pdo)
{
    $login = $params['usuario'];
    $senha = md5($params['senha'].".AMIX");
    $stmt  = $pdo->prepare("select login from usuario where login = :login and senha = :senha");
    $stmt->execute(array(':login' => $login, ':senha' => $senha));
    $nlinha = $stmt->rowCount();
    if ($nlinha > 0) {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        //trigger_error(print_r(array($nlinha, $usuario), true));
        //trigger_error(var_export(array($nlinha, $usuario), true));
        $_SESSION['usuario'] = $usuario['login'];
        header("Location: index.php");
    } else {
        echo "<script>alert ('Usuario ou senha não existe.')</script>";
    }
}
function usuarioLogado()
{
    if (array_key_exists('usuario', $_SESSION) && $_SESSION['usuario'] != "") {
        return true;
    } else {
        return false;
    }
}
function cadastraUsuario(array $params, $pdo)
{
    $ilogin = $params['usuario'];
    $isenha = md5($params['senha'].".AMIX");
    $stmt = $pdo->prepare("INSERT INTO 'ramais'.'usuario' ('login', `senha`) 
                            VALUES (:ilogin, :isenha);");
    $stmt->execute(array(':ilogin' => $ilogin, 'isenha' => $isenha));
}
function verificaUsuario(array $params, $pdo)
{
    $login = $params['usuario'];
    $stmt = $pdo->prepare("select login from usuario where login = :login");
    $stmt->execute(array(':login' => $login));
    $existe = $stmt->rowCount();
    if ($existe == 0) {
        cadastraUsuario($_POST, $pdo);
        return header("Location: login.php");
    } else {
        echo "<script>alert ('Este usuário já existe, tente outro.')</script>";
    }
}
