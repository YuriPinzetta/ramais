<?php

namespace amixsi;

class Usuario
{
    private $id;
    private $login;
		private $senha;
		private $perm_contato;
		private $perm_usuario;

    public static function fromArray($dados)
    {
        if (empty($dados['login'])) {
            throw new \Exception('Login não preenchido');
        }
        if (empty($dados['id'])) {
            throw new \Exception('id não preenchido');
        }
				$senha = isset($dados['senha']) ? $dados['senha'] : null;
				$perm_contato = isset($dados['perm_contato']) ? $dados['perm_contato'] : 0;
				$perm_usuario = isset($dados['perm_usuario']) ? $dados['perm_usuario'] : 0;
				$usuario = new Usuario($dados['id'], $dados['login'], $senha, $perm_contato, $perm_usuario);
        return $usuario;
		}

    public function __construct($id, $login, $senha, $perm_contato, $perm_usuario)
    {
        $this->setId($id);
        $this->setLogin($login);
        $this->setSenha($senha);
        $this->setPcontato($perm_contato);
        $this->setPusuario($perm_usuario);
    }
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
    public function getLogin()
    {
        return $this->login;
    }

    public function setLogin($login)
    {
        $this->login = $login;
    }
    public function getSenha()
    {
        return $this->senha;
    }

    public function setSenha($senha)
    {
        $this->senha = $senha;
		}

    public function getPcontato()
    {
        return $this->perm_contato;
		}

    public function setPcontato($perm_contato)
    {
        $this->perm_contato = $perm_contato;
		}

    public function getPusuario()
    {
        return $this->perm_usuario;
		}

    public function setPusuario($perm_usuario)
    {
        $this->perm_usuario = $perm_usuario;
		}
}
