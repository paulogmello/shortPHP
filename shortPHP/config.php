<!-- shortPHP um framework simples para conexões de banco de dados + utilitarios -->
<!-- Desenvoldido por Paulo Guilherme de Mello -->

<!-- CONEXÃO DO BANCO DE DADOS -->
<?php

require_once "Class.php"; //Conexão com banco de dados;
$meuBd = new shortPHP('localhost', 'glossario', 'root', '');
$bd = $meuBd->selecionar('usuarios', '*', '');


?>
