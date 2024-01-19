<?php 
// FUNÇÕES PARA ARRAYS

trait fArray {
  
    static function arredondamento(...$array){
        // ARREDONDA VÁRIOS NÚMEROS
        try{
            $novoArray = [];
            foreach($array as $i=>$items){
                $novoArray[$i] = shortPHP::arredondar($items);
            }
            return $novoArray;
        } catch (Error $erro) {
            echo "Erro: " . $erro->getMessage();
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

    static function ajudaArray(){
        echo "<h3>Funções de Array</h3><br>";
        echo '<b>arredondamento(...$array)</b>: Arredonda vários números<br>';
        echo '<b>arrayJavascript($arrayPHP, $nomeArrayJavascript)</b>: Transforma um array PHP em Javascript<br>';
        echo '----------------------<br>';
    }
}
?>