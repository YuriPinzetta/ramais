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
        $multi = (isset($dados[0]) ? true : false);
        if ($multi) {
            $ramais = array();
            foreach ($dados as $ramal) {
                $ramais[] = self::fromArray($ramal);
            }
            return $ramais;
        }
        $id = isset($dados['id']) ? $dados['id'] : null;
        $ramal = new Ramal($dados['tipo'], $dados['id_contato'], $dados['ramal'], $id);
        return $ramal;
    }

    public function __construct($tipo, $id_contato, $numero, $id = null)
    {
        $this->setTipo($tipo);
        $this->setIdcontato($id_contato);
        $this->setNumero($numero);
        $this->setId($id);
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
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
}
