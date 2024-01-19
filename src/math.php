<?php

// FUNÇÕES ARITMÉTICAS
trait Math
{
    static function numerico($numero)
    {
        if (is_numeric($numero) == true) {
            return true;
        } else {
            return false;
        }
    }

    static function somar(...$numeros)
    {
        // SOMA TODOS OS ITENS DENTRO DO ARRAY
        $valor = 0;
        foreach ($numeros as $items) {
            $valor += $items;
        }
        return $valor;
    }

    static function subtrair(...$numeros)
    {
        // SUBTRAI TODOS OS ITENS DENTRO DO ARRAY
        $valor = NULL;
        foreach($numeros as $items){
            if($valor == NULL){
                $valor = $items;
            } else {
                $valor -= $items;
            }
        }
        return $valor;
    }

    static function multiplicar(...$numeros){
        // MULTIPLICA TODOS OS ITENS DENTRO DO ARRAY
        $valor = NULL;
        foreach($numeros as $items){
            if($valor == NULL){
                $valor = $items;
            } else {
                $valor *= $items;
            }
        }
        return $valor;
    }

    static function dividir(...$numeros){
        // DIVIDE TODOS OS ITENS DENTRO DO ARRAY
        $valor = NULL;
        foreach($numeros as $items){
            if($valor == NULL){
                $valor = $items;
            } else {
                $valor /= $items;
            }
        }
        return $valor;
    }

    static function dados($min, $max)
    {
        // RETORNA UM NÚMERO ALEATÓRIO ENTRE OS PARÂMETROS APRESENTADOS
        if (self::numerico($min) == true && self::numerico($max) == true) {
            return rand($min, $max);
        } else {
            echo "Não é um número";
        }
    }

    static function arredondar($numero)
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

    static function ajudaMath()
    {
        echo "<h3>Funções de Math</h3><br>";
        echo '<b>numerico($numero)</b>: Retorna true se for do tipo numérico<br>';
        echo '<b>media(...$numero)</b>: Retorna a média aritimética simples dos números<br>';
        echo '<b>dados($min, $max)</b>: Retorna um valor aleatório entre o min e o max<br>';
        echo '<b>arredondar($numero)</b>: Retorna o valor arredondado<br>';
        echo '----------------------<br>';
    }
}
