<?php
	$dsn = 'mysql:host=localhost;dbname=ramais';
	$username = 'yuri';
	$password = 'Pinzetta';
	try {
		$pdo = new PDO($dsn, $username, $password);
		$stmt = $pdo->prepare('
		select contato.contato, 
		ramal.ramal 
		from ramal join contato 
		on contato.id = ramal.id_contato
		'	);
		$stmt->execute();
		$array = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$fd = fopen('contato_ramal.txt', 'w+');
		if ($fd === false) {
			exit(1);
		}
		foreach($array as $ramalContato){
			$linha = $ramalContato['contato'] . "\t" . $ramalContato['ramal'] . "\n";
			fputs($fd , $linha);
		}
		fclose($fd);
	} catch (PDOException $ex) {
			printf("Exception: %s\n", $ex->getMessage());
			trigger_error($ex->getTraceAsString());
		}
?>
