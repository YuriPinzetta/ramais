<?php

namespace amixsi;

class UsuarioDAO
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
		}

    public function valida(array $params)
    {
        $login = $params['usuario'];
        $senha = md5($params['senha'].".AMIX");
        $stmt  = $this->pdo->prepare("select login from usuario where login = :login and senha = :senha");
        $stmt->execute(array(':login' => $login, ':senha' => $senha));
        $nlinha = $stmt->rowCount();
        if ($nlinha > 0) {
            $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);
						return $usuario['login'];;
        }
				return null;
		}
		
    public function logado()
    {
        if (array_key_exists('usuario', $_SESSION) && $_SESSION['usuario'] != "") {
            return true;
        } else {
            return false;
        }
    }
    public function verifica(array $params)
    {
        $login = $params['usuario'];
        $stmt = $this->pdo->prepare("select login from usuario where login = :login");
        $stmt->execute(array(':login' => $login));
        return $stmt->rowCount() > 0;
		}

    public function cadastra(array $params)
    {
        $ilogin = $params['usuario'];
        $isenha = md5($params['senha'].".AMIX");
        $perm_usuario = $params['pusuario'];
        $perm_contato = $params['pcontato'];
        $stmt = $this->pdo->prepare("INSERT INTO usuario (login, senha, perm_usuario, perm_contato)
														VALUES (:ilogin, :isenha, :perm_usuario, :perm_contato);");
				$stmt->execute(array(':ilogin' => $ilogin, 'isenha' => $isenha, 
					':perm_usuario' => $perm_usuario, ':perm_contato' => $perm_contato));
		}

    public function listar(array $params)
    {
        $id = !empty($params['id']) ? $params['id'] : null;
        $login = !empty($params['login']) ? $params['login'] : null;
        $filtros = array();
        if ($id !== null) {
            $filtros[] = "id = $id";
        }
        $stmt = $this->pdo->prepare('select id, login, senha, perm_usuario, perm_contato	from usuario'.(count($filtros) > 0 ? ' where '.implode(' and ', $filtros) : '').'');
        $stmt->execute();
        $usuarios_foreach = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $usuarios_return = array();
        foreach ($usuarios_foreach as $usuarios) {
            $usuarios_return[] = Usuario::fromArray($usuarios);
				}
        return $usuarios;
		}

    /*public function nivel(array $params)
		{
				$id = $params['id'];
        $nivel = $params['niveis'];
        $stmt = $this->pdo->prepare('update usuario set nivel=:nivel WHERE id = :id');
        $stmt->execute(array(':nivel' => $nivel, ':id' => $id));
		}*/
}
