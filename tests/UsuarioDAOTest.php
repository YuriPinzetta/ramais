<?php

use Mockery as m;
use amixsi\UsuarioDAO;
use amixsi\Usuario;

class UsuarioDAOTest extends PHPUnit_Framework_TestCase
{

	public function tearDown()
	{
		m::close();
	}

	public function testeValida()
	{
		$params = array('usuario' => 'yuri', 'senha' => 'yuri');
		$login = $params['usuario'];
		$senha = md5($params['senha'].".AMIX");

		$usuario = array(array('id' => 1, 'login' => 'yuri'));

		$stmtMock = m::mock('PDOStatement')
			->shouldReceive('execute')
			->with(array(':login' => $login, ':senha' => $senha))
			->andReturn(true)
            ->shouldReceive('rowCount')
            ->andReturn(1)
			->shouldReceive('fetch')
			->with(\PDO::FETCH_ASSOC)
			->andReturn($usuario)
			->getMock();

		$pdoMock = m::mock('PDO')
			->shouldReceive('prepare')
			->andReturn($stmtMock)
			->getMock();

	
		$usuarioDao = new UsuarioDAO($pdoMock);

		$result = $usuarioDao->valida($params);

        $expectedResult = $usuario;

        $this->assertEquals($expectedResult, $result);
	}

    public function testeValida2()
    {
        $params = array('usuario' => 'yuri', 'senha' => 'yuri');
        $login = $params['usuario'];
        $senha = md5($params['senha'].".AMIX");

        $usuario = array(array('id' => 1, 'login' => 'yuri'));

        $stmtMock = m::mock('PDOStatement')
            ->shouldReceive('execute')
            ->with(array(':login' => $login, ':senha' => $senha))
            ->andReturn(true)
            ->shouldReceive('rowCount')
            ->andReturn(0)
            ->getMock();

        $pdoMock = m::mock('PDO')
            ->shouldReceive('prepare')
            ->andReturn($stmtMock)
            ->getMock();


        $usuarioDao = new UsuarioDAO($pdoMock);

        $result = $usuarioDao->valida($params);

        $this->assertNull($result);

    }

    public function testelogado()
    {
        $session = $_SESSION["usuario"] = "";

        $usuarioDao = new UsuarioDAO($session);

        $usuarioDao->logado();
    }

    public function testelogado2()
    {
        $session = $_SESSION["usuario"] = "yuri";

        $usuarioDao = new UsuarioDAO($session);

        $usuarioDao->logado();
    }

	public function testeVerifica()
	{
		$params = array('usuario' => 'yuri');
		$login = $params['usuario'];

		$stmtMock = m::mock('PDOStatement')
			->shouldReceive('execute')
			->with(array(':login' => $login))
            ->andReturn(true)
            ->shouldReceive('rowCount')
            ->andReturn(1)
			->getMock();

		$pdoMock = m::mock('PDO')
			->shouldReceive('prepare')
			->andReturn($stmtMock)
			->getMock();
	
		$usuarioDao = new UsuarioDAO($pdoMock);

        $usuarioDao->verifica($params);

	}

    public function testeCadastra()
    {
        $params = array('usuario' => 'yuri', 'senha' => 'yuri', 'pusuario' => 7, 'pcontato' => 7);
        $ilogin = $params['usuario'];
        $perm_usuario = $params['pusuario'];
        $perm_contato = $params['pcontato'];
        $isenha = md5($params['senha'].".AMIX");

        $stmtMock = m::mock('PDOStatement')
            ->shouldReceive('execute')
            ->with(array(':ilogin' => $ilogin, ':isenha' => $isenha, ':perm_usuario' => $perm_usuario, ':perm_contato' => $perm_contato))
            ->andReturn(true)
            ->getMock();

        $pdoMock = m::mock('PDO')
            ->shouldReceive('prepare')
            ->andReturn($stmtMock)
            ->getMock();

        $usuarioDao = new UsuarioDAO($pdoMock);

        $usuarioDao->cadastra($params);

    }

    public function testeListar()
    {
        $params = array('id' => 1, 'login' => 'yuri', 'senha' => 'yuri', 'pusuario' => 7, 'pcontato' => 7, 'filtro' => 'yuri');

        $uruarios_foreach = array(
            array('id' => 1, 'login' => 'yuri', 'senha' => 'yuri', 'pusuario' => 7, 'pcontato' => 7)
        );

        $usuarios_return = array();

        $stmtMock = m::mock('PDOStatement')
            ->shouldReceive('execute')
            ->andReturn(true)
            ->shouldReceive('fetchAll')
            ->with(\PDO::FETCH_ASSOC)
            ->andReturn($uruarios_foreach)
            ->getMock();

        $pdoMock = m::mock('PDO')
            ->shouldReceive('prepare')
            ->andReturn($stmtMock)
            ->getMock();

        $usuarioDao = new UsuarioDAO($pdoMock);

        $result = $usuarioDao->listar($params);
        $usuarios = array('id' => 1, 'login' => 'yuri', 'senha' => 'yuri', 'pusuario' => 7, 'pcontato' => 7);
        $expectedResult = array(
            $usuarios_return[] = Usuario::fromArray($usuarios)
        );

        $this->assertEquals($expectedResult, $result);

    }

    public function testeConsulta()
    {
        $usuario = array('id' => 1, 'login' => 'yuri', 'senha' => 'yuri', 'pusuario' => 7, 'pcontato' => 7);

        $stmtMock = m::mock('PDOStatement')
            ->shouldReceive('execute')
            ->with(array(':id' => 1))
            ->andReturn(true)
            ->shouldReceive('fetch')
            ->with(\PDO::FETCH_ASSOC)
            ->andReturn($usuario)
            ->getMock();

        $pdoMock = m::mock('PDO')
            ->shouldReceive('prepare')
            ->andReturn($stmtMock)
            ->getMock();

        $usuarioDao = new UsuarioDAO($pdoMock);

        $usuarioDao->consulta('1');

    }

    public function testeConsulta2()
    {
        $usuario = null;

        $stmtMock = m::mock('PDOStatement')
            ->shouldReceive('execute')
            ->with(array(':id' => 1))
            ->andReturn(true)
            ->shouldReceive('fetch')
            ->with(\PDO::FETCH_ASSOC)
            ->andReturn($usuario)
            ->getMock();

        $pdoMock = m::mock('PDO')
            ->shouldReceive('prepare')
            ->andReturn($stmtMock)
            ->getMock();

        $usuarioDao = new UsuarioDAO($pdoMock);

        $result = $usuarioDao->consulta('1');

        $this->assertNull($result);
    }

    public function testealtera()
    {
        $params = array('id' => 1, 'usuario' => 'yuri', 'senha' => 'yuri', 'pusuario' => 7, 'pcontato' => 7);
        $id = $params['id'];
        $login = $params['usuario'];
        $senha = !empty($params['senha']) ? $params['senha'] : null;
        $csenha = md5($senha.".AMIX");
        $perm_usuario = $params['pusuario'];
        $perm_contato = $params['pcontato'];

        $stmtMock = m::mock('PDOStatement')
            ->shouldReceive('execute')
            ->with(array(':login' => $login, ':senha' => $csenha, ':perm_usuario' => $perm_usuario, ':perm_contato' => $perm_contato, ':id' => $id))
            ->andReturn(true)
            ->getMock();

        $pdoMock = m::mock('PDO')
            ->shouldReceive('prepare')
            ->andReturn($stmtMock)
            ->getMock();

        $usuarioDao = new UsuarioDAO($pdoMock);

        $usuarioDao->altera($params);
    }

    public function testealtera2()
    {
        $params = array('id' => 1, 'usuario' => 'yuri', 'pusuario' => 7, 'pcontato' => 7);
        $id = $params['id'];
        $login = $params['usuario'];
        $senha = !empty($params['senha']) ? $params['senha'] : null;
        $perm_usuario = $params['pusuario'];
        $perm_contato = $params['pcontato'];

        $stmtMock = m::mock('PDOStatement')
            ->shouldReceive('execute')
            ->with(array(':login' => $login, ':perm_usuario' => $perm_usuario, ':perm_contato' => $perm_contato, ':id' => $id))
            ->andReturn(true)
            ->getMock();

        $pdoMock = m::mock('PDO')
            ->shouldReceive('prepare')
            ->andReturn($stmtMock)
            ->getMock();

        $usuarioDao = new UsuarioDAO($pdoMock);

        $usuarioDao->altera($params);

    }

    public function testedeleta()
    {

        $id = 1;

        $stmtMock = m::mock('PDOStatement')
            ->shouldReceive('execute')
            ->with(array(':id' => $id))
            ->andReturn(true)
            ->getMock();

        $pdoMock = m::mock('PDO')
            ->shouldReceive('prepare')
            ->andReturn($stmtMock)
            ->getMock();

        $usuarioDao = new UsuarioDAO($pdoMock);

        $usuarioDao->deleta($id);
    }
}
