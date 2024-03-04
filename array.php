<?php
namespace ShortPHP;
trait fArray
{

    static function arredondamento(...$array)
    {
        // ARREDONDA VÁRIOS NÚMEROS
        try {
            $novoArray = [];
            foreach ($array as $i => $items) {
                $novoArray[$i] = \shortPHP::arredondar($items);
            }
            return $novoArray;
        } catch (\Error $erro) {
            echo "Erro: " . $erro->getMessage();
        }
    }

    static function arrayJavascript($arrayPHP, $nomeArrayJavascript)
    {
        // CONVERTE UM ARRAY PHP PARA JAVASCRIPT
        echo "<script>
        let $nomeArrayJavascript = [";
        foreach ($arrayPHP as $items) {
            echo "`$items`,";
        }
        echo "] </script>";
    }

    public function bulkSelecionar($tabela, $linhas = "*", $param = "WHERE 1")
    {
        $tabela = str_replace(" ", "", $tabela);
        $tabela = explode(',', $tabela);
        $bulk = [];

        foreach ($tabela as $i => $items) {
            //    TRATAMENTO DAS LINHAS
            if (is_array($linhas) == true) {
                $linhasSelecionadas = $linhas[$i];
            } else if ($linhas != "*") {
                $linhasSelecionadas = $linhas;
            } else {
                $linhasSelecionadas = "*";
            }

            if (is_array($param) == true) {
                $paramSelecionadas = $param[$i];
            } else if ($param != "WHERE 1") {
                $paramSelecionadas = $param;
            } else {
                $paramSelecionadas = "WHERE 1";
            }

            $bulk[$items] = $this->selecionar($items, $linhasSelecionadas, $paramSelecionadas);
        }
        return $bulk;
    }
}
