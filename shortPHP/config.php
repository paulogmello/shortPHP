<?php
/**
 * shortPHP - Um framework simples para PHP.
 * PHP Version 8.0
 *
 * @see       https://github.com/paulogmello/shortPHP projeto no github
 *
 * @author    Paulo Guilherme de Mello <phpmailer@synchromedia.co.uk>
 */

require_once "Class.php"; //Conexão com a classe;
$meuBd = new shortPHP('localhost', 'glossario', 'root', '');
$bd = $meuBd->selecionar('usuarios', '*', '');

$a = shortPHP::arrendondamento(3.5, 5.8, 3.1);
print_r($a);
?>
