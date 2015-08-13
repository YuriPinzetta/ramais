<?php

use Mockery as m;
use amixsi\ContatoDAO;
use amixsi\Contato;
use amixsi\RamalDAO;
use amixsi\Ramal;

class ContatoDAOTest extends PHPUnit_Framework_TestCase
{

	public function tearDown()
  {
		m::close();
	}

	public function testInserir(){
		$nome = 'Yuri';
		$cargo = 'Desenvolvedor';
		$stmtMock = m::mock('PDOStatement')
			->shouldReceive('execute')
			->with(array(':cargo' => $cargo, ':contato' => $nome))
			->andReturn(true)
			->getMock();
		$pdoMock = m::mock('PDO')
			->shouldReceive('prepare')
			->andReturn($stmtMock)
			->getMock();

		$contato = new Contato($nome, $cargo);

		$contatoDao = new ContatoDAO($pdoMock);

		$contatoDao->inserir($contato);
		
	}

	public function testeconsulta()
	{
		$contato = array('id' => 1, 'contato' => 'Yuri', 'cargos' => 'AMIX');
		
		$id = $contato['id'];

		$stmtMock = m::mock('PDOStatement')
			->shouldReceive('execute')
			->with(array(':id' => $id))
			->andReturn(true)
			->shouldReceive('fetch')
			->with(\PDO::FETCH_ASSOC)
			->andReturn($contato)
			->getMock();
		
		$pdoMock = m::mock('PDO')
			->shouldReceive('prepare')
			->andReturn($stmtMock)
			->getMock();
		
			
		$contatoDao = new ContatoDAO($pdoMock);
		
		$result = $contatoDao->consulta($id);
		$contato = new Contato('Yuri', 'AMIX', 1);
		$expectedResult = $contato;

		$this->assertEquals($expectedResult, $result);
	}

	public function testelistar()
	{
		$params = array('id_contato' => 1, 'tipos' => 'interno', 'cargos' => 'AMIX');
		
		$contatos_foreach = array(array('id' => 1, 'cargos' => 'AMIX', 'contato' => 'Copa 1'));
		$ramal = new Ramal('Casa', 1, 333, 1);
		$ramais = array($ramal);
		$contatos_return = array();

		$stmtMock = m::mock('PDOStatement')
			->shouldReceive('execute')
			->andReturn(true)
			->shouldReceive('fetchAll')
			->with(\PDO::FETCH_ASSOC)
			->andReturn($contatos_foreach)
			->getMock();
		
		$pdoMock = m::mock('PDO')
			->shouldReceive('prepare')
			->andReturn($stmtMock)
			->getMock();

		$ramalDaoMock = m::mock('RamalDao')
			->shouldReceive('listar')
			->andReturn($ramais)
			->getMock();

		$contatoDao = new ContatoDAO($pdoMock, $ramalDaoMock);
		
		$result = $contatoDao->listar($params);
		$contato = new Contato('Copa 1', 'AMIX', 1);
		$contato->setRamais(array($ramal));
		$expectedResult = array(
			$contato
		);

		$this->assertEquals($expectedResult, $result);
	}

	public function testelistarCargos()
	{
		$cargos = array(array('cargos' => 'yuri'));

		$stmtMock = m::mock('PDOStatement')
			->shouldReceive('execute')
			->andReturn(true)
			->shouldReceive('fetchAll')
			->with(\PDO::FETCH_ASSOC)
			->andReturn($cargos)
			->getMock();
		
		$pdoMock = m::mock('PDO')
			->shouldReceive('prepare')
			->andReturn($stmtMock)
			->getMock();
		
		$contatoDao = new ContatoDAO($pdoMock);
		
		$expectedResult = $cargos;

		$result = $contatoDao->listarCargos();

		$this->assertEquals($expectedResult, $result);
	}

	public function testeAltera()
	{
		$params = array('contato' => 'yuri', 'cargos' => 'AMIX', 'id_contato' => 1);
		$contatos = $params['contato'];
		$cargos = $params['cargos'];
		$id = $params['id_contato'];
		$ramal = new Ramal('Casa', 1, 333, 1);
		$ramais = array($ramal);

		$stmtMock = m::mock('PDOStatement')
			->shouldReceive('execute')
			->with(array(':contatos' => $contatos, ':cargos' => $cargos, ':id' => $id))
			->andReturn(true)
			->getMock();
		
		$pdoMock = m::mock('PDO')
			->shouldReceive('prepare')
			->andReturn($stmtMock)
			->getMock();

		$ramalDaoMock = m::mock('RamalDao')
			->shouldReceive('altera')
			->andReturn($id, $ramais)
			->getMock();

		$contatoDao = new ContatoDAO($pdoMock, $ramalDaoMock);

		$contatoDao->altera($params, $ramais);
	}

	public function testeDeleta()
	{
		$params = array('id_contato' => 1, 'contato' => 'Yuri', 'cargos' => 'AMIX');

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
	
		$contatoDao = new ContatoDAO($pdoMock);

		$result = $contatoDao->deleta($params);

		$this->assertTrue($result);
	}

}
