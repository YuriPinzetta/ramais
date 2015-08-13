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

	/*public function testeValida()
	{
		$params = array('usuario' => 'yuri', 'senha' => 'yuri');
		$login = $params['usuario'];
		$senha = md5($params['senha'].".AMIX");
		$nlinha = m::mock('PDOStatement')
			->shouldReceive('rowConunt')
			->andReturn(1)
			->getMock();
		$usuario = array(array('id' => 1, 'login' => 'yuri'));

		$stmtMock = m::mock('PDOStatement')
			->shouldReceive('execute')
			->with(array(':login' => $login, ':senha' => $senha))
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

		$usuarioDao->valida($params);
	}*/

	public function testeVerifica()
	{
		$params = array('usuario' => 'yuri');
		$login = $params['usuario'];

		$stmtMock = m::mock('PDOStatement')
			->shouldReceive('execute')
			->with(array(':login' => $login))
			->andReturn(true)
			->getMock();

		$pdoMock = m::mock('PDO')
			->shouldReceive('prepare')
			->andReturn($stmtMock)
			->getMock();
	
		$usuarioDao = new UsuarioDAO($pdoMock);

		$usuarioDao->verifica($params);
	}
}
