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
		$params = array('id_contato' => 1, 'ramal' => 333,'tipos' => 'interno');
		$id_contato = $params['id_contato'];
		$tipos = $params['tipos'];

		$binds = array(':id_contato' => $id_contato);
		if($tipos){
			$binds[':tipos'] = $tipos;
		}

		$ramais = array(array('id_contato' => 1, 'ramal' => 333, 'tipo' => 'interno'));
	
		$stmtMock = m::mock('PDOStatement')
			->shouldReceive('execute')
			->with($binds)
			->andReturn(true)
			->shouldReceive('fetchAll')
			->with(\PDO::FETCH_ASSOC)
			->andReturn($ramais)
			->getMock();

		$pdoMock = m::mock('PDO')
			->shouldReceive('prepare')
			->andReturn($stmtMock)
			->getMock();

		$ramalDao = new RamalDAO($pdoMock);

		$result = $ramalDao->listar($params);
		$ramaisObj = new Ramal('interno', 1, 333);
		$expectedResult = array(
			$ramaisObj
		);

		$this->assertEquals($expectedResult, $result);
	}

	public function testealteraRamal2()
	{
		$id_contato = 1;
		$params = array('tipos' => 'interno', 'ramal' => 333, 'id' => 1);
		$tipos = $params['tipos'];
		$ramal = $params['ramal'];
		$id = $params['id'];

		$falseMock = m::mock('PDOStatement')
			->shouldReceive('execute')
			->andReturn(false)
			->getMock();

		$pdoMock = m::mock('PDO')
			->shouldReceive('prepare')
			->andReturn($falseMock)
			->getMock();
	
		$ramalDao = new RamalDAO($pdoMock);

		$ramalDao->alteraRamal($id_contato, $params);
	}

	public function testealteraRamal()
	{
		$id_contato = 1;
		$params = array('tipos' => 'interno', 'ramal' => 333, 'id' => 1);
		$tipos = $params['tipos'];
		$ramal = $params['ramal'];
		$id = $params['id'];

		$stmtMock = m::mock('PDOStatement')
			->shouldReceive('execute')
			->with(array(':tipos' => $tipos, ':ramal' => $ramal, ':id_contato' => $id_contato, ':id' => $id))
			->andReturn(true)
			->getMock();

		$pdoMock = m::mock('PDO')
			->shouldReceive('prepare')
			->andReturn($stmtMock)
			->getMock();
	
		$ramalDao = new RamalDAO($pdoMock);

		$ramalDao->alteraRamal($id_contato, $params);
	}

	public function testeDeleta()
	{
		$params = array('id_contato' => 1);
		$id = $params['id_contato'];

		$stmtMock = m::mock('PDOStatement')
			->shouldReceive('execute')
			->with(array(':id' => $id))
			->andReturn(true)
			->getMock();

		$pdoMock = m::mock('PDO')
			->shouldReceive('prepare')
			->andReturn($stmtMock)
			->getMock();
	
		$ramalDao = new RamalDAO($pdoMock);

		$result = $ramalDao->deleta($params);

		$this->assertTrue($result);	
	}
}
