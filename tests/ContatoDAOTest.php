<?php

use Mockery as m;
use amixsi\ContatoDAO;
use amixsi\Contato;

class ContatoDAOTest extends PHPUnit_Framework_TestCase
{
	public function tearDown()
  {
		m::close();
	}		
	public function testInserir(){
		$nome = 'Yuri';
		$cargo = 'Desenvolvedor';
		$pdoMock = m::mock('PDO');
		//$sqlMock = 'INSERT INTO contato(cargos, contato) VALUES (:cargo,:contato)';
		$stmtMock = m::mock('PDOStatement')
			->shouldReceive('execute')
			->with(array(':cargo' => $cargo, ':contato' => $nome))
			->andReturn(true)
			->getMock();
		$pdoMock = m::mock('PDO')
			->shouldReceive('prepare')
			//->with($sqlMock)
			->andReturn($stmtMock)
			->getMock();

		$contato = new Contato($nome, $cargo);

		$contatoDao = new ContatoDAO($pdoMock);

		$contatoDao->inserir($contato);
		
	}

	public function testeconsulta()
	{
		$contato = array('id' => 1, 'contato' => 'Yuri', 'cargos' => 'Amix');
		
		$pdoMock = m::mock('PDO');
		//$sqlMock = 'select id, contato, cargos from contato WHERE id = :id';
		
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
			//->with($sqlMock)
			->andReturn($stmtMock)
			->getMock();
		
		$id = $contato['id'];
			
		$contatoDao = new ContatoDAO($pdoMock);
		
		$contatoDao->consulta($id);
	}
}
