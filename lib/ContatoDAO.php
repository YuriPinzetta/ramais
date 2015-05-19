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
	public function listar(array $params)
	{
		$id_contato = !empty($params['id_contato']) ? $params['id_contato'] : null;
		$tipos = !empty($params['tipos']) ? $params['tipos'] : null;
		$cargos = !empty($params['cargos']) ? $params['cargos'] : null;
		$filtros = array();
		if ($id_contato !== null) {
			$filtros[] = "id = $id_contato";
		}
		if ($cargos !== null) {
			$filtros[] = "cargos = '$cargos'";
		}
		$stmt = $this->pdo->prepare("
		select id,
		cargos,
		contato 
		from contato" . (count($filtros) > 0 ? 'where ' . implode(' and ', $filtros) : '') . "
		");
		$stmt->execute();
		$contatos_foreach = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		$contatos_return = array();
		$ramalDAO = new RamalDAO($this->pdo);
		foreach ($contatos_foreach as $contato) {
			$params = array(
				'id_contato' => $contato['id'],
				'tipos' => $tipos
			);
			$ramais = $ramalDAO->listar($params);
			$contato['ramais'] = $ramais;
			if ($tipos === null || count($ramais) > 0) {
				$contatos_return[] = $contato;
			}
		}
		return $contatos_return;
	}
}
