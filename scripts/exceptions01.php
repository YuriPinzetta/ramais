<?php


function somar_contadores(array $contadores)
{
    $soma = 0;
    foreach ($contadores as $contador) {
        printf("%s: %d\n", $contador, is_numeric($contador));
        if (!is_numeric($contador)) {
            throw new Exception('Não é um numero');
        }
        $soma += $contador;
    }
    return $soma;
}



$contadores = array(
    10,
    45,
    56,
    70,
    'a',
    3
);

try {
    $soma = somar_contadores($contadores);

    printf("Total: %d\n", $soma);
} catch (Exception $e) {
    printf("Erro: %s\n%s\n", $e->getMessage(), $e->getTraceAsString());
}
printf('Teste');
