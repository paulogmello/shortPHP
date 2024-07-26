<?php
namespace ShortPHP;
trait Sessions
{
    static function startSession($nome, $regenerarId = 300)
    {
        // INICIAR SESSÃO
        session_name($nome);
        session_start();

        // SEGURANÇA
        if (isset($_SESSION['sessao-ultimo-acesso'])) {
            // Verificar ultimo acesso
            $tempo = time() - $_SESSION['sessao-ultimo-acesso'];

            if ($tempo > $regenerarId) {
                session_regenerate_id(true);
                $_SESSION['sessao-ultimo-acesso'] = time();
            }
        } else {
            $_SESSION['sessao-ultimo-acesso'] = time();
        }

        return true;
    }

    static function closeSession($nome)
    {
        session_start($nome);
        $_SESSION[$nome][] = array(); // Destruir os arrays
        session_destroy();
    }

    static function cookie($nome, $valor, $dataExpirar = '', $caminho = "/", $dominio = '', $seguranca = true)
    {
        if ($dataExpirar == "") {
            $dataExpirar = time() + (3600 * 1);
        } else {
            return $dataExpirar;
        }

        if ($dominio == "") {
            $dominio = $_SERVER['HTTP_HOST'];
        } else {
            return $dominio;
        }
        setcookie($nome, $valor, $dataExpirar, $caminho, $dominio, $seguranca);
        return true;
    }
}
