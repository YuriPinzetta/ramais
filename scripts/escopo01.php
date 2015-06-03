<?php

$a = 1;
$b = 2;
$c = 3;
$d = 4;

function f($c, $b, $a)
{
    global $d;
    var_dump($a, $b, $c, $d);
}

f($a, $b, $c);
