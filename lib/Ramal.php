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
			if($multi)
			{
				foreach($dados as $key => $dado){
					if (empty($dado['ramal'])) {
						throw new Exception('Ramal não preenchido');
					}
					$ramal[] = new Ramal($dado['tipo'], $dado['id_contato'], $dado['ramal'], $dado['id']);
				}
			} else
			{
					if (empty($dados['ramal'])) {
						throw new Exception('Ramal não preenchido');
					}
					$ramal = new Ramal($dados['tipo'], $dados['id_contato'], $dados['ramal'], $dados['id']);
			}
				return $ramal;
    }

		public function __construct($tipo, $id_contato, $numero, $id)
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
