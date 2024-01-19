<?php

/**
 * shortPHP - Um framework simples para PHP.
 * Necessário PHP Version 8.0
 *
 * @see       https://github.com/paulogmello/shortPHP projeto no github
 * @author    Paulo Guilherme de Mello <paulogmello.com>
 * @version   1.0
 */

// Importar arquivos de funções
require_once 'basic.php';
require_once 'database.php';
require_once 'math.php';
require_once 'array.php';

/** Classe principal do shortPHP
 *  Não faça nenhuma alteração neste arquivo;
 *  Um manual composto de todas as funções estará disponível no github
 */

class shortPHP
{
    use Basic; // Funções Gerais
    use Math; // Funções de Matemática
    use Database; //Funções de Banco de dados
    use fArray; // Funções de Array;

    static function ajuda()
    // Função para ajudar a entender as funções
    {
        echo "Componentes do <b>ShortPHP</b>:<br>";
        echo '----------------------<br>';
        echo shortPHP::ajudaDatabase();
        echo shortPHP::ajudaArray();
        echo shortPHP::ajudaMath();
    }
}