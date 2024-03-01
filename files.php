<?php
trait Files
{
    static function enviarArquivo($arquivo, $pasta, $nomeFinal = false, $criptografar = false)
    // Envia um arquivo para um diretório escolhido
    {
        $tipo = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
        if ($nomeFinal == false) {
            $nomeArquivo = $arquivo['name'];
        } else {
            $nomeArquivo = $nomeFinal . ".$tipo";
        }

        if ($criptografar == true && $nomeFinal == false) {
            $nomeArquivo = md5($nomeArquivo) . ".$tipo";
        } else if ($criptografar == true && $nomeFinal != false) {
            $nomeArquivo = $nomeFinal . md5($nomeArquivo) . ".$tipo";
        }

        $tmp = $arquivo['tmp_name'];
        $arquivo = $pasta . $nomeArquivo;
        if (!is_dir($pasta)) {
            if (!mkdir($pasta, 0777, true)) {
                return false;
            }
        }

        if (!move_uploaded_file($tmp, $arquivo)) {
            // Falha ao mover o arquivo
            return false;
        }

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

    static function verExtensao($arquivo)
    {
        return pathinfo($arquivo, PATHINFO_EXTENSION);
    }
}
