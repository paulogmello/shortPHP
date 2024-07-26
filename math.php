<?php

namespace ShortPHP;

trait Math

// FUNÇÕES ARITMÉTICAS
{
    static function numeric($numero)
    {
        if (is_numeric($numero) == true) {
            return true;
        } else {
            return false;
        }
    }

    static function sum(...$numeros)
    {
        // SOMA TODOS OS ITENS DENTRO DO ARRAY
        $valor = 0;
        foreach ($numeros as $items) {
            $valor += $items;
        }
        return $valor;
    }

    static function sub(...$numeros)
    {
        // SUBTRAI TODOS OS ITENS DENTRO DO ARRAY
        $valor = NULL;
        foreach ($numeros as $items) {
            if ($valor == NULL) {
                $valor = $items;
            } else {
                $valor -= $items;
            }
        }
        return $valor;
    }

    static function multiply(...$numeros)
    {
        // MULTIPLICA TODOS OS ITENS DENTRO DO ARRAY
        $valor = NULL;
        foreach ($numeros as $items) {
            if ($valor == NULL) {
                $valor = $items;
            } else {
                $valor *= $items;
            }
        }
        return $valor;
    }

    static function divide(...$numeros)
    {
        // DIVIDE TODOS OS ITENS DENTRO DO ARRAY
        $valor = NULL;
        foreach ($numeros as $items) {
            if ($valor == NULL) {
                $valor = $items;
            } else {
                $valor /= $items;
            }
        }
        return $valor;
    }

    static function dices($min, $max)
    {
        // RETORNA UM NÚMERO ALEATÓRIO ENTRE OS PARÂMETROS APRESENTADOS
        if (self::numerico($min) == true && self::numerico($max) == true) {
            return rand($min, $max);
        } else {
            echo "Not a number";
        }
    }

    static function round($numero)
    {
        // ARREDONDA O NÚMERO
        try {
            if (is_float($numero) == true) {
                $decimal = explode('.', $numero);
                if ($decimal[1] < 5) {
                    return floor($numero);
                } else if ($decimal[1] == 5) {
                    return $numero;
                } else {
                    return ceil($numero);
                }
            } else if (is_numeric($numero) == true) {
                return $numero;
            }
        } catch (\Error $erro) {
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

    static function decimais($valor, $casas = 2)
    {
        return number_format($valor, $casas, '.', '');
    }

    static function bytesParaMb($valor)
    {
        $valor = ($valor / 1024) / 1024;
        return \shortPHP::decimais($valor, 2) . " MB";
    }
    static function bytesParaGb($valor)
    {
        $valor = ($valor / 1024) / 1024;
        $valor = $valor / 1024;
        return \shortPHP::decimais($valor, 2) . " GB";
    }
}
