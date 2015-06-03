<?php
$dsn = 'mysql:host=localhost;dbname=ramais';
$username = 'yuri';
$password = 'Pinzetta';
$id = 12;
try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->beginTransaction();
    $stmt = $pdo->prepare('
		select * from contato
		where id = :id
	');
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute(array(':id' => $id));
    foreach ($stmt as $row) {
        print_r($row);
    }
    $pdo->commit();
} catch (PDOException $ex) {
    $pdo->rollBack();
    printf("Exception: %s\n", $ex->getMessage());
    trigger_error($ex->getTraceAsString());
}
