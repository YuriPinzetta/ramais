<?php
	$dsn = 'mysql:host=localhost;dbname=ramais';
	$username = 'yuri';
	$password = 'Pinzetta';
	try{
		$pdo = new PDO($dsn, $username, $password);
		$pdo->begintransction();
		$stmt = $pdo->prepare('');
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$stmt->execute(array());
		foreach($stmt as $row){
			Print_r($row);
		}
	}
	catch(PDOException $ex){
		$pdo->rollBack();
		printf("Exception: %s\n", $ex->getMessage());
		trigger_error($ex->getTraceAsString());
	}
?>
