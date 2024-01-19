<?php 
trait Basic {
    static function verHora(){
        // Retorna HORA atual
        date_default_timezone_set('America/Sao_Paulo');
        return date('H');
    }

    static function verMinutos(){
        // Retorna Minuto atual
        date_default_timezone_set('America/Sao_Paulo');
        return date('i');
    }
    static function verHoraMinutos(){
        // Retorna o horário atual
        date_default_timezone_set('America/Sao_Paulo');
        return date('H:i');
    }
    static function cumprimentar(){
        // Cumprimenta de acordo com o horário
        $hora = self::verHora();
        if($hora >= 06 && $hora < 12) {
            return "Bom-Dia";
        } else if ($hora >= 12 && $hora < 18){
            return "Boa-Tarde";
        } else {
            return "Boa-Noite";
        }
    }
}
?>