<?php

require_once 'database.php';
require_once 'math.php';
require_once 'array.php';

class shortPHP
{
    use Math; // Funções de Matemática
    use Database; //Funções de Banco de dados
    use fArray; // Funções baseadas em array;

    static function componentes(){
        echo "Componentes do ShortPHP:<br>";
        echo shortPHP::testeArray();
        echo shortPHP::testeMath();
    }

}
