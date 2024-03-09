<?php

namespace ShortPHP;

trait Files
{
    static function lerArquivo($arquivo)
    {
        // LÊ O ARQUIVO O RETORNA
        return file_get_contents($arquivo);
    }

    static function criarDiretorio($dir)
    {
        try {
            if (!is_dir($dir)) {
                if (!mkdir($dir, 0777, true)) {
                    return false;
                }
            }
        } catch (\Error $e) {
            echo "Houve um erro na criação do diretório: $e";
        }
    }

    static function escreverArquivo($arquivo, $conteudo, $sobrescrever = false)
    // ESCREVE OU CRIA UM ARQUIVO NOVO COM O CONTEÚDO ADICIONADO
    {
        try {
            $dir = pathinfo($arquivo)['dirname'] . '/';
            self::criarDiretorio($dir);
            if ($sobrescrever == false) {
                file_put_contents($arquivo, $conteudo, FILE_APPEND | LOCK_EX);
            } else {
                file_put_contents($arquivo, $conteudo, LOCK_EX);
            }
            return true;
        } catch (\ERROR $e) {
            echo "Houve um erro ao escrever o arquivo: " . $e;
        }
    }

    static function enviarArquivo($arquivo, $pasta = './    ', $nomeFinal = false, $criptografar = false)
    // ENVIA UM ARQUIVO PARA UM DIRETÓRIO
    {

        try {
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
            self::criarDiretorio($pasta);

            if (!move_uploaded_file($tmp, $arquivo)) {
                // Falha ao mover o arquivo
                return false;
            }
        } catch (\ERROR $e) {
            echo "Houve um erro no envio do arquivo para a pasta: $e";
        }
    }

    static function deletarArquivo($nome, $pasta, $criptogradado = false)
    {
        // DELETA UM ARQUIVO
        try {
            if ($criptogradado == true) {
                $tipo = pathinfo($nome, PATHINFO_EXTENSION);
                $nomeArquivo = md5($nome) . ".$tipo";
            } else {
                $nomeArquivo = $nome;
            }
            unlink($pasta . '/' . $nomeArquivo);
            return true;
        } catch (\Error $e) {
            echo "Houve um erro na exclusão do arquivo: $e";
        }
    }

    static function espacoLivre($pasta = "C:/")
    {
        // VERIFICA O ESPAÇO LIVRE
        return disk_free_space($pasta);
    }

    static function verExtensao($arquivo)
    {
        // VERIFICA A EXTENSÃO DO ARQUIVO
        return pathinfo($arquivo, PATHINFO_EXTENSION);
    }

    private static function verificarModuloImagick()
    {
        $modulo = "imagick";
        if (extension_loaded($modulo)) {
            return true;
        } else {
            return false;
        }
    }

    private static function selecionarImagem($caminho)
    {
        // SELECIONA UMA IMAGEM PARA O IMAGICK
        return new \Imagick(realpath($caminho));
    }

    static function converter($arquivo, $formato, $pasta = NULL)
    {
        // CONVERTE UMA IMAGEM OU ARQUIVO PARA UM OUTRO FORMATO
        try {
            if (self::verificarModuloImagick() == true) {
                $temp = self::selecionarImagem($arquivo);
                $fileName = pathinfo($arquivo, PATHINFO_FILENAME);
                $realpath = realpath($arquivo);
                $realpath = str_replace("\\", "/", $realpath);
                $realpath = explode('/', $realpath);
                array_pop($realpath);
                $realpath = implode("/", $realpath);
                if ($pasta != NULL) {
                    self::criarDiretorio($pasta);
                }
                $temp->setImageFormat($formato);
                $temp->writeImage("$realpath/$pasta/$fileName.$formato");
                $temp->clear();
                $temp->destroy();
                return true;
            } else {
                echo "O módulo do <b>Image Magick</b> não está instalado.<br>É necessário estar instalado no PHP para o funcionamento desta função";
            }
        } catch (\Error $e) {
            echo "Houve erro na conversão da imagem: $e";
        }
    }

    static function converterBase64($base64, $altura = 300, $largura = 300)
    {
        // CONVERTE UMA IMAGEM PARA STRING BASE 64
        $base64 = preg_replace('/^data:image\/(png|jpeg|jpg);base64,/', '', $base64);
        $image = base64_decode($base64);

        $im = new \Imagick();
        $im->readImageBlob($image);
        $im->thumbnailImage($altura, $largura, true);

        $output = $im->getimageblob();
        $im->getFormat();
        return $output;
    }

    static function renderizarImagem($output, $type)
    {
        // RENDERIZA UMA IMAGEM
        header("Content-type: $type");
        echo $output;
    }

    static function converterImagemBase64($img)
    {
        // CONVERTE UMA IMAGEM EM BASE64
        $mime = mime_content_type($img);
        $dados = file_get_contents($img);
        $base64_imagem = 'data:' . $mime . ';base64,' . base64_encode($dados);
        return $base64_imagem;
    }
}
