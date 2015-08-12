<?php

use Mockery as m;
use amixsi\ContatoDAO;
use amixsi\Contato;
use amixsi\RamalDAO;
use amixsi\Ramal;

class RamalDAOTest extends PHPUnit_Framework_TestCase
{

	public function tearDown()
	{
		m::close();
	}

	public function testInserir()
	{
		$id_contato = 1;
		$tipos = 'interno';
		$ramais = 333;

		$firstStmtMock = m::mock('PDOStatement')
			->shouldReceive('execute')
			->andReturn(true)
			->shouldReceive('fetch')
			->with(\PDO::FETCH_ASSOC)
			->andReturn(array('id' => 1))
			->getMock();

		$id_ramal = 1;

		$stmtMock = m::mock('PDOStatement')
			->shouldReceive('execute')
			->with(array(':id_contato' => $id_contato, ':id_ramal' => $id_ramal, ':tipos' => $tipos, 'ramal' => $ramais))
			->andReturn(true)
			->getMock();

		$pdoMock = m::mock('PDO')
			->shouldReceive('prepare')
			->times(2)
			->andReturn($firstStmtMock, $stmtMock)
			->getMock();

		$ramal = new Ramal($tipos, $id_contato, $ramais);

		$ramalDao = new RamalDAO($pdoMock);

		$ramalDao->inserir($ramal);
	}

	public function testeListar()
	{
	
	}
}
