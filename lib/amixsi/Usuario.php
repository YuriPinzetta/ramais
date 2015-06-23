<?php

namespace amixsi;

class Usuario
{
    private $id;
    private $login;
		private $senha;
		private $nivel;

    public static function fromArray($dados)
    {
        if (empty($dados['login'])) {
            throw new \Exception('Login não preenchido');
        }
        if (empty($dados['id'])) {
            throw new \Exception('id não preenchido');
        }
				$senha = isset($dados['senha']) ? $dados['senha'] : null;
				$nivel = isset($dados['nivel']) ? $dados['nivel'] : null;
				$usuario = new Usuario($dados['id'], $dados['login'], $senha, $nivel);
        return $usuario;
		}

    public function __construct($id, $login, $senha, $nivel)
    {
        $this->setId($id);
        $this->setLogin($login);
        $this->setSenha($senha);
        $this->setNivel($nivel);
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

    public function getNivel()
    {
        return $this->nivel;
		}

    public function setNivel($nivel)
    {
        $this->nivel = $nivel;
		}
}
