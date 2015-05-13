<?php
	$dsn = 'mysql:host=localhost;dbname=ramais';
	$username = 'yuri';
	$password = 'Pinzetta';
	try {
		$pdo = new PDO($dsn, $username, $password);
		$stmt = $pdo->prepare('
		select contato.contato,
		contato.cargos,
		ramal.tipo,
		ramal.ramal 
		from ramal join contato 
		on contato.id = ramal.id_contato
		'	);
		$stmt->execute();
		$array = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$fd = fopen('tudo.txt', 'w+');
		if ($fd === false) {
			exit(1);
		}

		$maxColunas = array(
			'contato' => 0,
			'cargos' => 0,
			'tipo' => 0,
			'ramal' => 0,
		);
		foreach($array as $ramalContato){
			foreach ($ramalContato as $nome => $valor) {
				$len = mb_strlen($valor, 'utf-8');
				if ($len > $maxColunas[$nome]) {
					$maxColunas[$nome] = $len + 1;
				}
			}
		}
		var_dump($maxColunas);

		foreach($array as $ramalContato){
			$colunas = array();
			foreach ($ramalContato as $nome => $valor) {
				$colunas[] = $valor . str_pad('', $maxColunas[$nome] - mb_strlen($valor, 'utf-8'));
			}
			$linha = implode('|', $colunas) . "\n";
			fputs($fd , $linha);
		}
		fclose($fd);
	} catch (PDOException $ex) {
			printf("Exception: %s\n", $ex->getMessage());
			trigger_error($ex->getTraceAsString());
		}
?>
