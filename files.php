<?php

namespace ShortPHP;

trait Files
{
    static function readFile($file)
    {
        // LÊ O ARQUIVO O RETORNA
        return file_get_contents($file);
    }

    static function makeDir($dir)
    {
        try {
            if (!is_dir($dir)) {
                if (!mkdir($dir, 0777, true)) {
                    return false;
                }
            }
        } catch (\Error $e) {
            echo "Error: $e";
        }
    }

    static function writeFile($file, $content, $overwrite = false)
    // ESCREVE OU CRIA UM ARQUIVO NOVO COM O CONTEÚDO ADICIONADO
    {
        try {
            $dir = pathinfo($file)['dirname'] . '/';
            self::makeDir($dir);
            if ($overwrite == false) {
                file_put_contents($file, $content, FILE_APPEND | LOCK_EX);
            } else {
                file_put_contents($file, $content, LOCK_EX);
            }
            return true;
        } catch (\ERROR $e) {
            echo "Error: " . $e;
        }
    }

    static function sendFile($file, $folder = './    ', $finalName = false, $encode = false)
    // ENVIA UM ARQUIVO PARA UM DIRETÓRIO
    {

        try {
            $fileType = pathinfo($file['name'], PATHINFO_EXTENSION);
            if ($finalName == false) {
                $fileName = $file['name'];
            } else {
                $fileName = $finalName . ".$fileType";
            }

            if ($encode == true && $finalName == false) {
                $fileName = md5($fileName) . ".$fileType";
            } else if ($encode == true && $finalName != false) {
                $fileName = $finalName . md5($fileName) . ".$fileType";
            }

            $tmp = $file['tmp_name'];
            $file = $folder . $fileName;
            self::makeDir($folder);

            if (!move_uploaded_file($tmp, $file)) {
                // Falha ao mover o arquivo
                return false;
            }
        } catch (\ERROR $e) {
            echo "Error: $e";
        }
    }

    static function deleteFile($name, $folder, $encoded = false)
    {
        // DELETA UM ARQUIVO
        try {
            if ($encoded == true) {
                $fileType = pathinfo($name, PATHINFO_EXTENSION);
                $fileName = md5($name) . ".$fileType";
            } else {
                $fileName = $name;
            }
            unlink($folder . '/' . $fileName);
            return true;
        } catch (\Error $e) {
            echo "Houve um erro na exclusão do arquivo: $e";
        }
    }

    static function seeFreeSpace($folder = "C:/")
    {
        // VERIFICA O ESPAÇO LIVRE
        return disk_free_space($folder);
    }

    static function seeExtension($file)
    {
        // VERIFICA A EXTENSÃO DO ARQUIVO
        return pathinfo($file, PATHINFO_EXTENSION);
    }

    private static function seeImagickModule()
    {
        $module = "imagick";
        if (extension_loaded($module)) {
            return true;
        } else {
            return false;
        }
    }

    private static function selectImage($caminho)
    {
        // SELECIONA UMA IMAGEM PARA O IMAGICK
        return new \Imagick(realpath($caminho));
    }

    static function transform($file, $formato, $folder = NULL)
    {
        // CONVERTE UMA IMAGEM OU ARQUIVO PARA UM OUTRO FORMATO
        try {
            if (self::seeImagickModule() == true) {
                $temp = self::selectImage($file);
                $fileName = pathinfo($file, PATHINFO_FILENAME);
                $realpath = realpath($file);
                $realpath = str_replace("\\", "/", $realpath);
                $realpath = explode('/', $realpath);
                array_pop($realpath);
                $realpath = implode("/", $realpath);
                if ($folder != NULL) {
                    self::makeDir($folder);
                }
                $temp->setImageFormat($formato);
                $temp->writeImage("$realpath/$folder/$fileName.$formato");
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

    static function changetoBase64($base64, $height = 300, $width = 300)
    {
        // CONVERTE UMA IMAGEM PARA STRING BASE 64
        $base64 = preg_replace('/^data:image\/(png|jpeg|jpg);base64,/', '', $base64);
        $image = base64_decode($base64);

        $im = new \Imagick();
        $im->readImageBlob($image);
        $im->thumbnailImage($height, $width, true);

        $output = $im->getimageblob();
        $im->getFormat();
        return $output;
    }

    static function renderImage($output, $type)
    {
        // RENDERIZA UMA IMAGEM
        header("Content-type: $type");
        echo $output;
    }

    static function convertImgTo64($img)
    {
        // CONVERTE UMA IMAGEM EM BASE64
        $mime = mime_content_type($img);
        $dados = file_get_contents($img);
        $base64_imagem = 'data:' . $mime . ';base64,' . base64_encode($dados);
        return $base64_imagem;
    }
}
