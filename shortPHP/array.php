<?php 
// FUNÇÕES PARA ARRAYS

trait fArray {
    static function testeArray(){
        return "As <b>funções Array</b> estão funcionando<br>";
    }

    static function arrendondamento(...$array){
        $novoArray = [];
        foreach($array as $i=>$items){
            $novoArray[$i] = shortPHP::arredondar($items);
        }
        return $novoArray;
    }
}
?>