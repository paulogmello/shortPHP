<?php

// FUNÇÕES ARITMÉTICAS
trait Math
{

    static function testeMath()
    {
        // TESTE DE FUNCIONALIDADE
        return "As <b>funções Matemáticas</b> estão funcionando<br>";
    }

    static function dados($min, $max)
    {
        // RETORNA UM NÚMERO ALEATÓRIO ENTRE OS PARÂMETROS APRESENTADOS
        return rand($min, $max);
    }

    static function arredondar($numero)
    {
        // ARREDONDA O NÚMERO
        $decimal = explode('.', $numero);
        if ($decimal[1] < 5) {
            return floor($numero);
        } else if ($decimal[1] == 5) {
            return $numero;
        } else {
            return ceil($numero);
        }
    }

    static function media(...$numeros)
    {
        // RECEBE OS NÚMEROS E FAZ A MÉDIA
        $quantidade = count($numeros);
        $soma = 0;
        foreach ($numeros as $items) {
            $soma = $soma + $items;
        }
        $resultado = $soma / $quantidade;
        return self::arredondar($resultado);
    }
}
