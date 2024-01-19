<?php 
// FUNÇÕES PARA ARRAYS

trait fArray {
    static function testeArray(){
        return "As <b>funções Array</b> estão funcionando<br>";
    }

    static function arredondamento(...$array){
        // ARREDONDA VÁRIOS NÚMEROS
        try{
            $novoArray = [];
            foreach($array as $i=>$items){
                $novoArray[$i] = shortPHP::arredondar($items);
            }
            return $novoArray;
        } catch (Exception $erro) {
            echo $erro->getMessage();
        }
    }

    static function arrayJavascript($arrayPHP, $nomeArrayJavascript){
        // CONVERTE UM ARRAY PHP PARA JAVASCRIPT
        echo "<script>
        let $nomeArrayJavascript = [";
        foreach($arrayPHP as $items){
            echo "`$items`,";
        }
        echo "] </script>";
    }
}
?>