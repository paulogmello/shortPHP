<?php
trait Files
{
    static function enviarArquivo($arquivo, $pasta, $criptografar = false)
    // Envia um arquivo para um diretório escolhido
    {
        $tipo = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
        if ($criptografar == true) {
            $nomeArquivo = md5($arquivo['name']) . ".$tipo";
        } else {
            $nomeArquivo = $arquivo['name'];
        }
        $arquivo = $pasta . $nomeArquivo;
        $tmp = $_FILES['imagem']['tmp_name'];
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
}
