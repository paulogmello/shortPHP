<?php

// FUNÇÕES ARITMÉTICAS
trait Math
{

    static function testeMath()
    {
        // TESTE DE FUNCIONALIDADE
        return "As <b>funções Matemáticas</b> estão funcionando<br>";
    }

    static function numerico($numero){
        if (is_numeric($numero) == true){
            return true;
        } else {
            return false;
        }
    }

    static function dados($min, $max)
    {
        // RETORNA UM NÚMERO ALEATÓRIO ENTRE OS PARÂMETROS APRESENTADOS
        if(self::numerico($min) == true && self::numerico($max) == true){
            return rand($min, $max);
        } else {
            echo "Não é um número";
        }
    }

    static function arredondar($numero)
    {
        // ARREDONDA O NÚMERO
        try{
            if(is_float($numero) == true){
                $decimal = explode('.', $numero);
                if ($decimal[1] < 5) {
                    return floor($numero);
                } else if ($decimal[1] == 5) {
                    return $numero;
                } else {
                    return ceil($numero);
                }
            } else if(is_numeric($numero) == true) {
                return $numero;
            }
        } catch (Error $erro) {
            echo $erro->getMessage();
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
