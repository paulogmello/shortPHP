<?php

namespace ShortPHP;

trait fArray
{
    static function chunk($array, $size = 1)
    {
        return array_chunk($array, $size);
    }

    static function arrayToJson($array)
    {
        // Converte array em JSON
        return json_encode($array);
    }
    static function jsonToArray($array)
    {
        // Converte json em Array
        return json_decode($array, true);
    }

    static function rounding(...$array)
    {
        // ARREDONDA VÁRIOS NÚMEROS
        try {
            $novoArray = [];
            foreach ($array as $i => $items) {
                $novoArray[$i] = \shortPHP::rounding($items);
            }
            return $novoArray;
        } catch (\Error $erro) {
            echo "Erro: " . $erro->getMessage();
        }
    }

    static function arrayJavascript($arrayPHP, $nameArrayJavascript)
    {
        // CONVERTE UM ARRAY PHP PARA JAVASCRIPT
        echo "<script>
        let $nameArrayJavascript = [";
        foreach ($arrayPHP as $items) {
            echo "`$items`,";
        }
        echo "] </script>";
    }

    public function bulkSelect($table, $row = "*", $param = "WHERE 1")
    {
        $table = str_replace(" ", "", $table);
        $table = explode(',', $table);
        $bulk = [];

        foreach ($table as $i => $items) {
            //    TRATAMENTO DAS row
            if (is_array($row) == true) {
                $rowSelecionadas = $row[$i];
            } else if ($row != "*") {
                $rowSelecionadas = $row;
            } else {
                $rowSelecionadas = "*";
            }

            if (is_array($param) == true) {
                $paramSelecionadas = $param[$i];
            } else if ($param != "WHERE 1") {
                $paramSelecionadas = $param;
            } else {
                $paramSelecionadas = "WHERE 1";
            }

            $bulk[$items] = $this->selecionar($items, $rowSelecionadas, $paramSelecionadas);
        }
        return $bulk;
    }
}
