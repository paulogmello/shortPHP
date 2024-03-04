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
require_once 'array.php';
require_once 'basic.php';
require_once 'database.php';
require_once 'files.php';
require_once 'mail.php';
require_once 'math.php';
require_once 'sessions.php';

/** Classe principal do shortPHP
 *  Não faça nenhuma alteração neste arquivo;
 *  Um manual composto de todas as funções estará disponível no github
 */

class shortPHP
{
    use ShortPHP\Basic; // Funções Gerais
    use ShortPHP\Database; //Funções de Banco de dados
    use ShortPHP\fArray; // Funções de Array;
    use ShortPHP\Files; //Funções de envio de arquivos
    use ShortPHP\Mail; //Funções de envio de e-mails
    use ShortPHP\Math; // Funções de Matemática
    use ShortPHP\Sessions; //Funções e configurações de sessões

    static function versao()
    {
        echo ("shortPHP 1.3");
    }
}
