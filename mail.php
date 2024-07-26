<?php
namespace ShortPHP;
trait Mail
{
    static function sendMessage($meuEmail, $destino, $assunto, $mensagem)
    {
        if ($meuEmail != "") {
            $header = array(
                'From' => $meuEmail
            );
            mail($destino, $assunto, $mensagem, $header);
        } else {
            mail($destino, $assunto, $mensagem);
        }
    }
}
