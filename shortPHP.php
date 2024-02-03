<?php

/**
 * shortPHP - Uma biblioteca pra simplificar consultas MySQL.
 * Necessário PHP Version 8.0
 *
 * @see       https://github.com/paulogmello/shortPHP lib no github
 * @see       https://www.paulogmello/projects/shortPHP
 * @author    Paulo Guilherme de Mello <paulogmello.com>
 * @version   1.0
 */

// Importar arquivos de funções
require_once 'basic.php';
require_once 'database.php';
require_once 'math.php';
require_once 'array.php';
require_once 'files.php';

/** Classe principal do shortPHP
 *  Não faça nenhuma alteração neste arquivo;
 *  Um manual composto de todas as funções estará disponível no github
 */

class shortPHP
{
    use Basic; // Funções Gerais
    use Math; // Funções de Matemática
    use fArray; // Funções de Array;
    use Database; //Funções de Banco de dados
    use Files; //Funções de envio de arquivos

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
