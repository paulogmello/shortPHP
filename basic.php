<?php
trait Basic
{
    static function verHora($timezone = "America/Sao_Paulo")
    {
        // Retorna HORA atual
        date_default_timezone_set($timezone);
        return date('H');
    }

    static function verMinutos($timezone = "America/Sao_Paulo")
    {
        // Retorna Minuto atual
        date_default_timezone_set($timezone);
        return date('i');
    }
    static function verHoraMinutos($timezone = "America/Sao_Paulo")
    {
        // Retorna o horário atual
        date_default_timezone_set($timezone);
        return date('H:i');
    }

    static function verDia($timezone = "America/Sao_Paulo")
    {
        // Retorna o horário atual
        date_default_timezone_set($timezone);
        return date('d');
    }

    static function verMes($timezone = "America/Sao_Paulo")
    {
        // Retorna o horário atual
        date_default_timezone_set($timezone);
        return date('m');
    }

    static function verAno($timezone = "America/Sao_Paulo")
    {
        // Retorna o horário atual
        date_default_timezone_set($timezone);
        return date('Y');
    }

    static function verData($timezone = "America/Sao_Paulo")
    {
        // Retorna o horário atual
        date_default_timezone_set($timezone);
        return date('d/m/Y');
    }

    static function converterData($data){
        $data = explode("-", $data);
        $ano = $data[0]; $mes = $data[1]; $dia = $data[2];
        return "$dia/$mes/$ano";
    }

    static function cumprimentar()
    {
        // Cumprimenta de acordo com o horário
        $hora = self::verHora();
        if ($hora >= 06 && $hora < 12) {
            return "Bom-Dia";
        } else if ($hora >= 12 && $hora < 18) {
            return "Boa-Tarde";
        } else {
            return "Boa-Noite";
        }
    }

    static function hash($valor, $quantidade = false)
    // criptografa uma string, porém este comando nunca deve ser usado para senhas
    {
        $pass = md5($valor);
        if ($quantidade !== false) {
            if ($quantidade >= 3 && $quantidade <= 32) {
                return $pass = substr($pass, 0, $quantidade);
            } else if ($quantidade < 3) {
                return "É necessário uma quantidade mínima superior a 3 caracteres";
            } else if ($quantidade > 32) {
                return "Quantidade máxima de 32 atingida ou ultrapassada";
            }
        } else {
            return $pass;
        }
    }

    static function criptografar($string)
    // FAZ UMA CRIPTOGRAFIA SEGURA DE UMA STRING, É NECESSÁRIO USAR O AUTENTICA() PARA VERIFICAR A AUTENTICIDADE DO HASH
    {
        return password_hash($string, PASSWORD_DEFAULT);
    }

    static function autenticar($senha, $criptografia)
    // VERIFICA SE A CRIPTOGRAFIA COMBINA COM O PARÂMETRO PASSADO PELO USUÁRIO COM BASE NA CRIPTOGRAFIA DA FUNÇÃO CRIPTOGRAFAR()
    {
        return password_verify($senha, $criptografia);
    }
}
