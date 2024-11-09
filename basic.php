<?php

namespace ShortPHP;

trait Basic
{
    static function seeHour($timezone = "America/Sao_Paulo")
    {
        // Retorna HORA atual
        date_default_timezone_set($timezone);
        return date('H');
    }

    static function seeMinutes($timezone = "America/Sao_Paulo")
    {
        // Retorna Minuto atual
        date_default_timezone_set($timezone);
        return date('i');
    }
    static function seeHourMinutes($timezone = "America/Sao_Paulo")
    {
        // Retorna o horário atual
        date_default_timezone_set($timezone);
        return date('H:i');
    }

    static function seeDay($timezone = "America/Sao_Paulo")
    {
        // Retorna o horário atual
        date_default_timezone_set($timezone);
        return date('d');
    }

    static function seeMonth($timezone = "America/Sao_Paulo")
    {
        // Retorna o horário atual
        date_default_timezone_set($timezone);
        return date('m');
    }

    static function seeYear($timezone = "America/Sao_Paulo")
    {
        // Retorna o horário atual
        date_default_timezone_set($timezone);
        return date('Y');
    }

    static function seeDate($timezone = "America/Sao_Paulo")
    {
        // Retorna o horário atual
        date_default_timezone_set($timezone);
        return date('d/m/Y');
    }

    static function dateSQL($timezone = "America/Sao_Paulo")
    {
        date_default_timezone_set($timezone);
        return date('Y-m-d');
    }

    static function dateTimeSQL($timezone = "America/Sao_Paulo")
    {
        date_default_timezone_set($timezone);
        return date('Y-m-d H:i:s');
    }

    static function dateIdent($data)
    {
        $data = explode("-", $data);
        $ano = $data[0];
        $mes = $data[1];
        $dia = $data[2];
        return "$dia/$mes/$ano";
    }

    static function greetings($goodMorning = NULL, $goodAfternoon = NULL, $goodEvening = NULL)
    {
        // Cumprimenta de acordo com o horário
        $hora = self::seeHour();
        if ($hora >= 06 && $hora < 12) {
            if ($goodMorning == NULL) {
                return "Good-Morning";
            } else {
                return $goodMorning;
            }
        } else if ($hora >= 12 && $hora < 18) {
            if($goodAfternoon == NULL){
                return "Good-Afternoon";
            }
            return $goodAfternoon;
        } else {
            if($goodEvening == NULL){
                return "Good-Evening";
            }
            return $goodEvening;
        }
    }

    static function question($question, $yes, $not, $first = true){
        if($question == $first){
            return $yes;
        } else {
            return $not;
        }
    } 

    static function hash($value, $quantity = false)
    // criptografa uma string, porém este comando nunca deve ser usado para senhas
    {
        $pass = md5($value);
        if ($quantity !== false) {
            if ($quantity >= 3 && $quantity <= 32) {
                return $pass = substr($pass, 0, $quantity);
            } else if ($quantity < 3) {
                return "It's necessary an amount of 3 numbers or more";
            } else if ($quantity > 32) {
                return "Maximun quantity of 32 numbers exceeded";
            }
        } else {
            return $pass;
        }
    }

    static function encode($string)
    // FAZ UMA CRIPTOGRAFIA SEGURA DE UMA STRING, É NECESSÁRIO USAR O AUTENTICAR() PARA VERIFICAR A AUTENTICIDADE DO HASH
    {
        return password_hash($string, PASSWORD_DEFAULT);
    }

    static function auth($senha, $criptografia)
    // VERIFICA SE A CRIPTOGRAFIA COMBINA COM O PARÂMETRO PASSADO PELO USUÁRIO COM BASE NA CRIPTOGRAFIA DA FUNÇÃO CRIPTOGRAFAR()
    {
        return password_verify($senha, $criptografia);
    }

}
