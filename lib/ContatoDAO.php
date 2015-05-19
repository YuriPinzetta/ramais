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
}
