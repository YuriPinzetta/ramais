<?php
require('Contato.php');
require('Ramal.php');

use amixsi\Contato;
use amixsi\Ramal;

$contato2 = Contato::fromArray(array(
    'nome' => 'Edison'
));
$contato1 = new Contato('Yuri');
$ramal1 = new Ramal(1234);
$contato1->setRamais(array($ramal1));
var_dump($contato1);
var_dump($contato2);
