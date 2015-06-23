<?php

namespace amixsi;

class Contato
{
    private $id;
    private $nome;
    private $cargo;
    private $ramais;

    public static function fromArray($dados)
    {
				var_dump($dados);
        if (empty($dados['contato'])) {
            throw new \Exception('Contato não preenchido');
        }
        if (empty($dados['cargos'])) {
            throw new \Exception('Cargos não preenchido');
        }
				$id = isset($dados['id']) ? $dados['id'] : null;
        $contato = new Contato($dados['contato'], $dados['cargos'], $id);
        if (isset($dados['ramais'])) {
            $contato->setRamais($dados['ramais']);
        }
        return $contato;
    }

    public function __construct($nome, $cargo = null, $id = null)
    {
        $this->setId($id);
        $this->setNome($nome);
        if ($cargo !== null) {
            $this->setCargo($cargo);
        }
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setCargo($cargo)
    {
        $this->cargo = $cargo;
    }

    public function getCargo()
    {
        return $this->cargo;
    }

    public function setRamais(array $ramais)
    {
        $this->ramais = array();
        foreach ($ramais as $ramal) {
            if ($ramal instanceof Ramal) {
                $ramal->setContato($this);
                $this->ramais[] = $ramal;
            }
        }
    }
    public function getRamais()
    {
        return $this->ramais;
    }
}
