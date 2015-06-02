<?php

function string_position($texto, $parte) {
	// pegar primeira letra $parte
	$tmhT = strlen($texto);
	$tmhP = strlen($parte);
	$numero = 0;
	for($i = 0; $i < $tmhT; $i++){
		printf("{i: %d, texto{i}: %s, numero: %d, parte{numero}: %s}\n", $i, $texto{$i}, $numero, $parte{$numero});
		if($texto{$i} == $parte{$numero}){
			for($am = $i; $am < $tmhT && $numero < $tmhP; $am++){
				printf("{am: %d, texto{am}: %s, numero: %d, parte{numero}: %s}\n", $am, $texto{$am}, $numero, $parte{$numero});
				if($texto{$am} != $parte{$numero}){
					break;
				}
				$numero++;
			}				
			printf("{numero: %d, tmhP: %d}\n", $numero, $tmhP);
			if ($numero == $tmhP) {
				return $i;
			}
			return false;
		}
	}
	return false;
}
// encontrar a posição da primeira letra no $texto
// checar se a partir da posição encontrada todas as letras são iguais a $parte
// retornar posicão se todas as letras forem encontradas senão false
/*$parte = stripos($texto, $parte);
return $parte;
return $texto;*/
			/*for($num = $numero; $num < $tmhP; $num++){
				if($texto{$i} == $parte{$num}){
				}else{
				}
			}*/


$ret1 = string_position('Yuri Pinzetta', 'zetta') === 8;
printf("ret1: %s\n", $ret1);

$ret2 = string_position('Yuri Pinzetta', 'Yur') === 0;
printf("ret2: %s\n", $ret2);

$ret3 = string_position('Yuri Pinzetta', 'Amix') === false;
printf("ret3: %s\n", $ret3);

$ret4 = string_position('abc123 teste123', '123') === 3;
printf("ret4: %s\n", $ret4);
