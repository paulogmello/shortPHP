<?php
/**
 * shortPHP - Um framework simples para PHP.
 * PHP Version 8.0
 *
 * @see       https://github.com/paulogmello/shortPHP projeto no github
 * @author    Paulo Guilherme de Mello <paulogmello.com>
 * @version   1.0
 */

// Importar arquivos de funções
require_once 'database.php';
require_once 'math.php';
require_once 'array.php';

/** Classe principal do shortPHP
 *  Não faça nenhuma alteração neste arquivo;
 *  Um manual composto de todas as funções estará disponível no github
*/

class shortPHP
{
    use Math; // Funções de Matemática
    use Database; //Funções de Banco de dados
    use fArray; // Funções de Array;

    static function componentes()
    // Função para verificar se todos os componentes estão funcionando
    {
        echo "Componentes do ShortPHP:<br>";
        echo shortPHP::testeArray();
        echo shortPHP::testeMath();
    }
}
