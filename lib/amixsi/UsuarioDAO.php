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
        $stmt  = $this->pdo->prepare("select id, login from usuario where login = :login and senha = :senha");
        $stmt->execute(array(':login' => $login, ':senha' => $senha));
        $nlinha = $stmt->rowCount();
        if ($nlinha > 0) {
            $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $usuario;
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

    /**
     * @param array $params
     */
    public function cadastra(array $params)
    {
        $ilogin = $params['usuario'];
        $isenha = md5($params['senha'].".AMIX");
        $perm_usuario = !empty($params['pusuario']) ? $params['pusuario'] : 0;
        $perm_contato = !empty($params['pcontato']) ? $params['pcontato'] : 0;
        $stmt = $this->pdo->prepare("INSERT INTO usuario (login, senha, perm_usuario, perm_contato) VALUES (:ilogin, :isenha, :perm_usuario, :perm_contato)");
        $stmt->execute(array(':ilogin' => $ilogin, ':isenha' => $isenha, ':perm_usuario' => $perm_usuario, ':perm_contato' => $perm_contato));
    }

    public function listar(array $params)
    {
        foreach (array('id', 'filtro') as $name) {
            $$name = !empty($params[$name]) ? $params[$name] : null;
        }
        $filtros = array();
				$binds = array();
        if ($id !== null) {
            $filtros[] = "id = :id";
						$binds[':id'] = $id;
        }
        if ($filtro !== null) {
            $filtros[] = "login like concat('%', :filtro, '%')";
						$binds[':filtro'] = $filtro;
        }
        $stmt = $this->pdo->prepare('
            select
                id,
                login,
                senha,
                perm_usuario,
                perm_contato
            from usuario ' .
            ( count($filtros) > 0 ? ' where ' . implode(' and ', $filtros) : '' )
        );
        $stmt->execute($binds);
        $usuarios_foreach = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $usuarios_return = array();
        foreach ($usuarios_foreach as $usuarios) {
            $usuarios_return[] = Usuario::fromArray($usuarios);
				}
        return $usuarios_return;
    }
		
    public function consulta($id)
    {
        $stmt = $this->pdo->prepare('
            select
                id,
                login,
                perm_usuario,
                perm_contato
            from usuario
            where id = :id'
        );
        $stmt->execute(array(':id' => $id));
        $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($usuario) {
            return Usuario::fromArray($usuario);
        }
        return null;
    }

    public function altera(array $params)
    {
        $id = $params['id'];
        $login = $params['usuario'];
        $senha = !empty($params['senha']) ? $params['senha'] : null;
        $csenha = md5($senha.".AMIX");
        $perm_usuario = $params['pusuario'];
        $perm_contato = $params['pcontato'];
        if(!(is_null($senha))){
            $stmt = $this->pdo->prepare('
                update usuario set
                login=:login,
                senha=:senha,
                perm_usuario=:perm_usuario,
                perm_contato=:perm_contato
                WHERE id = :id');
            $stmt->execute(array(':login' => $login, ':senha' => $csenha, ':perm_usuario' => $perm_usuario,
                ':perm_contato' => $perm_contato, ':id' => $id));

        }else{
            $stmt = $this->pdo->prepare('
                update usuario set
                login=:login,
                perm_usuario=:perm_usuario,
                perm_contato=:perm_contato
                WHERE id = :id');
            $stmt->execute(array(':login' => $login, ':perm_usuario' => $perm_usuario, ':perm_contato' => $perm_contato, ':id' => $id));
        }
    }

    public function deleta($id)
    {
        $stmt = $this->pdo->prepare('delete from usuario where id = :id');
        $stmt->execute(array(':id' => $id));
    }
}
