<?php
trait Files
{
    static function enviarArquivo($arquivo, $pasta, $nomeFinal = false, $criptografar = false)
    // Envia um arquivo para um diretório escolhido
    {
        $tipo = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
        if ($criptografar == true) {
            $nomeArquivo = md5($arquivo['name']) . ".$tipo";
        } else if ($nomeFinal == false) {
            $nomeArquivo = $arquivo['name'];
        } else {
            $nomeArquivo = $nomeFinal . ".$tipo";
        }
        $tmp = $arquivo['tmp_name'];
        $arquivo = $pasta . $nomeArquivo;
        move_uploaded_file($tmp, $arquivo);
        return true;
    }

    static function deletarArquivo($nome, $pasta, $criptogradado = false)
    {
        try {
            if ($criptogradado == true) {
                $tipo = pathinfo($nome, PATHINFO_EXTENSION);
                $nomeArquivo = md5($nome) . ".$tipo";
            } else {
                $nomeArquivo = $nome;
            }
            unlink($pasta . '/' . $nomeArquivo);
            return true;
        } catch (Error $e) {
            echo "Houve um erro na exclusão do arquivo: $e";
        }
    }

    static function espacoLivre($pasta = "C:/")
    {
        return disk_free_space($pasta);
    }

    static function Livre($pasta = "C:/")
    {
        return disk_free_space($pasta);
    }

    static function verExtensao($arquivo){
        return pathinfo($arquivo, PATHINFO_EXTENSION);
    }
}
