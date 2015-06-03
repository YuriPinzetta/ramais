<?php
class OlaMundo
{
    public function OlaMundo()
    {
        return "Ola mundo do PHPOO!";
    }
}
$ola = new OlaMundo();
print $ola->OlaMundo();

class Pessoa
{
    private $nome;
    public function setNome($nome)
    {
        $this->nome = $nome;
    }
    public function getNome()
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
