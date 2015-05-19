<?php

namespace amixsi;

class Ramal
{
    private $id_contato;
    private $id;
    private $tipo;
    private $numero;
    private $contato;

    public static function fromArray($dados)
    {
        if (empty($dados['ramal'])) {
            throw new Exception('Ramal não preenchido');
        }
        $ramal = new Ramal($dados['tipos'], $dados['id_contato'], $dados['ramal']);
        return $ramal;
    }

    public function __construct($tipo, $id_contato, $numero)
    {
        $this->setTipo($tipo);
        $this->setIdcontato($id_contato);
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

    public function setIdcontato($id_contato)
    {
        $this->id_contato = $id_contato;
    }

    public function getIdcontato()
    {
        return $this->id_contato;
    }

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    public function getTipo()
    {
        return $this->tipo;
    }
}
