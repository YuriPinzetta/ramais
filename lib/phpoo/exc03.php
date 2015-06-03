<?php
class estatica
{
    public static $varStatic = "Variavel Estática<br>";
    public static function getStatic()
    {
        return Estatica::$varStatic;
    }
}
echo Estatica::getStatic();
