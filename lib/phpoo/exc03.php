<?php
class estatica{
 static $varStatic = "Variavel Estática<br>";
 static function getStatic()
 {
 	return Estatica::$varStatic;
 }
}
echo Estatica::getStatic();
