<?php

use Mockery as m;
use amixsi\ContatoDAO;
use amixsi\Contato;
use amixsi\RamalDAO;
use amixsi\Ramal;

class RamalTest extends PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        m::close();
    }

    public function testFromArray(){
        $id = 1;
        $id2 = 2;
        $id_contato = 1;
        $tipo = 'interno';
        $tipo2 = 'celular';
        $ramal = '123';
        $ramal2 = '321';
        $dados = array(
            array('id' => 1, 'id_contato' => 1, 'tipo' => 'interno', 'ramal' => 123),
            array('id' => 2, 'id_contato' => 1, 'tipo' => 'celular', 'ramal' => 321)
        );
        $contato = new Ramal($tipo, $id_contato, $ramal, $id);
        $contato2 = new Ramal($tipo2, $id_contato, $ramal2, $id2);
        $result = $contato->Fromarray($dados);
        $expectedResult = array(
            $contato,
            $contato2
        );

        $this->assertEquals($expectedResult, $result);
    }

}
