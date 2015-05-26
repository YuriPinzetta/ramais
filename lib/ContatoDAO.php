<?php

namespace amixsi;

class ContatoDAO
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function inserir(Contato $contato)
    {
        $cargo = $contato->getCargo();
        $contato = $contato->getNome();
        $stmt = $this->pdo->prepare('INSERT INTO contato(cargos, contato) VALUES (:cargo,:contato)');
        $stmt->execute(array(':cargo' => $cargo, ':contato' => $contato));
    }

    public function consulta($id)
    {
        $stmt = $this->pdo->prepare('select id, contato, cargos from contato WHERE id = :id');
        $stmt->execute(array(':id' => $id));
        $contato = $stmt->fetch(\PDO::FETCH_ASSOC);
        return Contato::fromArray($contato);
    }
   /* public function altera(Contato $contato, Ramal $ramal)
    {
        $contatos = $contato->getNome();
        $cargos = $contato->getCargo();
        $id = $contato->getId();
        $stmt = $this->pdo->prepare("update contato set contato=:contatos, cargos=:cargos WHERE id = :id");
        $stmt->execute(array(':contatos' => $contatos,':cargos' => $cargos,':id' => $id));
        alteraRamais($id, $ramais, $this->pdo);
	}*/
}
