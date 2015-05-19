<?php

namespace amixsi;

class Ramal
{
    private $contato;
    private $id;
    private $tipo;
    private $numero;

    public function __construct($numero)
    {
        $this->setNumero($numero);
    }

    public function setContato(Contato $contato)
    {
        $this->contato = $contato;
    }

    public function getContato()
    {
        return $this->contato;
    }

    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    public function getNumero()
    {
        return $this->numero;
    }
}
