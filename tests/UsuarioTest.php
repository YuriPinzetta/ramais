<?php

use Mockery as m;
use amixsi\UsuarioDAO;
use amixsi\Usuario;

class UsuarioTest extends PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        m::close();
    }

    public function testGetId()
    {
        $id = 1;
        $login   = 'yuri';
        $senha = 'yuri';
        $perm_contato = 7;
        $perm_usuario = 7;

        $usuario = new Usuario($id, $login, $senha, $perm_contato, $perm_usuario);
        $result = $usuario->getId();

        $this->assertEquals($id, $result);
    }

    public function testGetLogin()
    {
        $id = 1;
        $login   = 'yuri';
        $senha = 'yuri';
        $perm_contato = 7;
        $perm_usuario = 7;

        $usuario = new Usuario($id, $login, $senha, $perm_contato, $perm_usuario);
        $result = $usuario->getLogin();

        $this->assertEquals($login, $result);
    }

    public function testGetSenha()
    {
        $id = 1;
        $login   = 'yuri';
        $senha = 'yuri';
        $perm_contato = 7;
        $perm_usuario = 7;

        $usuario = new Usuario($id, $login, $senha, $perm_contato, $perm_usuario);
        $result = $usuario->getSenha();

        $this->assertEquals($senha, $result);
    }

    public function testGetPcontato()
    {
        $id = 1;
        $login   = 'yuri';
        $senha = 'yuri';
        $perm_contato = 7;
        $perm_usuario = 7;

        $usuario = new Usuario($id, $login, $senha, $perm_contato, $perm_usuario);
        $result = $usuario->getPcontato();

        $this->assertEquals($perm_contato, $result);
    }

    public function testGetPusuario()
    {
        $id = 1;
        $login   = 'yuri';
        $senha = 'yuri';
        $perm_contato = 7;
        $perm_usuario = 7;

        $usuario = new Usuario($id, $login, $senha, $perm_contato, $perm_usuario);
        $result = $usuario->getPusuario();

        $this->assertEquals($perm_usuario, $result);
    }
}
