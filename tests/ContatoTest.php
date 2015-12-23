<?php

use Mockery as m;
use amixsi\ContatoDAO;
use amixsi\Contato;
use amixsi\RamalDAO;
use amixsi\Ramal;

class ContatoTest extends PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        m::close();
    }

    public function testGetId()
    {
        $id = 1;
        $nome = 'yuri';
        $cargo = 'dev';
        $contato = new Contato($nome, $cargo, $id);
        $result = $contato->getId();

        $this->assertEquals($id, $result);
    }

}
