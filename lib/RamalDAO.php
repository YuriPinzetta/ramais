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
		$ramaisObj = array();
		foreach ($ramais as $ramal) {
						$ramaisObj[] = Ramal::fromArray($ramal);
		}
		return $ramaisObj;
	}
	public function altera($id_contato, array $ramais)
	{
		foreach ($ramais as $ramal) {
			self::alteraRamal($id_contato, $ramal);
		}
	}
	public function alteraRamal($id_contato, array $params)
	{
		$tipos = $params['tipos'];
		$ramal = $params['ramal'];
		$id = $params['id'];
		$stmt = $this->pdo->prepare("update ramal set tipo = :tipos, ramal = :ramal WHERE id_contato = :id_contato and id = :id");
		if($stmt->execute(array(':tipos' => $tipos,':ramal' => $ramal,':id_contato' => $id_contato, ':id' => $id)) == true){
			header("Location: index.php");
		}else{
    	echo "<script>alert ('Não houve alteração no banco, tente novamente.')</script>";
		}
	}
	function deleta(array $params)
	{
		$id = $params['id_contato'];
		$stmt = $this->pdo->prepare("delete from ramal WHERE id_contato = :id");
		$stmt->execute(array(':id' => $id));
		header("Location: index.php");
	}
}
