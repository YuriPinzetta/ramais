<?php
function db()
{
		static $pdo = null;
		if ($pdo !== null) {
						return $pdo;
		}
    $dsn='mysql:host=localhost;dbname=ramais';
    $username='yuri'; // Indique o nome do usuÃ¡rio que tem acesso
    $password='Pinzetta'; // Indique a senha do usuÃ¡rio
    try {
        $pdo = new PDO($dsn, $username, $password);
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $ex) {
        trigger_error($ex->getTraceAsString());
        throw $ex;
    }
    return $pdo;
}
