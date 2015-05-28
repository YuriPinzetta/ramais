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
	public function listar(array $params)
	{
		$id_contato = $params['id_contato'];
		$tipos = isset($params['tipos']) ? $params['tipos'] : null;
		$stmt = $this->pdo->prepare("select id_contato, id, ramal, tipo from ramal where id_contato = :id_contato
			" . ($tipos ? "and tipo = :tipos" : "") . "
		");
		$binds = array(':id_contato' => $id_contato);
		if ($tipos) {
						$binds[':tipos'] = $tipos;
		}
		$stmt->execute($binds);
		$ramais = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		return Ramal::fromArray($ramais);
		//return $ramais;
	}
}
