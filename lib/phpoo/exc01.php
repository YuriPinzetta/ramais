<?php
class OlaMundo{
	function OlaMundo()
	{
		return "Ola mundo do PHPOO!";
	}
}
$ola = new OlaMundo();
print $ola->OlaMundo();

class Pessoa{
	private $nome;
	function setNome($nome)
	{
		$this->nome = $nome;
	}
	function getNome()
	{
		return $this->nome;
	}
}
$joao = new Pessoa();
$joao->setNome("João Brito");
$pedro = new Pessoa();
$pedro->setNome("Pedro Ribeiro");

print '<b><br><br>Classe Pessoa:<br></b>';
print $joao->getNome();
print '<br>';
print $pedro->getNome();
