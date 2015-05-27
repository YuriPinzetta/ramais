<?php

namespace amixsi;

class RamalDAO
{
	private $pdo;

	public function __construct($pdo)
	{
		$this->pdo = $pdo;
	}
		
	public function inserir($ramal)
	{
		$id_contato = $ramal->getIdContato();
		$tipos = $ramal->getTipo();
		$ramais = $ramal->getNumero();

		if (empty($tipos) || empty($ramais)) {
			return header("HTTP/1.1 404 Bad Request");
		}
		
		$stmt = $this->pdo->prepare('select coalesce(max(id),0) + 1 as id from ramal');
		$stmt->execute();
		$result = $stmt->fetch(\PDO::FETCH_ASSOC);
		$id_ramal = $result['id'];
		$stmt = $this->pdo->prepare('INSERT INTO ramal(id_contato, id, tipo,ramal) 
			VALUES (:id_contato, :id_ramal, :tipos, :ramal)');
		$stmt->execute(array(':id_contato' => $id_contato,
		 ':id_ramal' => $id_ramal,
		 ':tipos' => $tipos, 'ramal' => $ramais));
		header("Location: index.php");
	}
	public function listar($ramal)
	{
		$id_contato = $ramal['id_contato'];
		$tipos = isset($ramal->setTipo) ? $ramal->setTipo : null;
		$stmt = $this->pdo->prepare("select id, ramal, tipo from ramal where id_contato = '$id_contato'
			" . ($tipos ? "and tipo = '$tipos'" : "") . "
		");
		$stmt->execute(array(':id_contato' => $id_contato, ':tipos' => $tipos));
		$ramais = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		return $ramais;
	}
	public function listarContatos(array $params)
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
		$stmt = $this->pdo->prepare("select id,	cargos, contato	from contato" . (count($filtros) > 0 ? ' where ' . implode(' and ', $filtros) : '') . "");
		$stmt->execute();
		$contatos_foreach = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		$contatos_return = array();
	 	foreach ($contatos_foreach as $contato) {
			$params = array(
					'id_contato' => $contato['id'],
					'tipos' => $tipos
			);
			$ramais = $this->listar($ramal);
			$contato['ramais'] = $ramais;
			if ($tipos === null || count($ramais) > 0) {
			$contatos_return[] = $contato;
		 }
		}
		return $contatos_return;
	}
}
