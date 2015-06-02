<?php

function min_pos_val(array $lista) {
	$pos = null;
	$val = null;
	foreach ($lista as $i => $num) {
		if ($val === null) {
			$pos = $i;
			$val = $num;
		}
		if ($num < $val) {
			$pos = $i;
			$val = $num;
		}
	}
	return array('pos' => $pos, 'val' => $val);
}

function ordenar_array(array $lista) {
	$lista2 = array();
	while(($n = count($lista)) > 0) {
		$min = min_pos_val($lista);
		$lista2[] = $min['val'];
		array_splice($lista, $min['pos'], 1);
	}
	return $lista2;
}


$list1 = array(
				3,
				45,
				10,
				0,
				67,
				30,
				9,
				89,
				-2
);


$list = ordenar_array($list1);
$list_formatted = var_export($list, true);
$expected_list = <<<OEL
array (
  0 => -2,
  1 => 0,
  2 => 3,
  3 => 9,
  4 => 10,
  5 => 30,
  6 => 45,
  7 => 67,
  8 => 89,
)
OEL;
var_dump($list, $list_formatted == $expected_list);
