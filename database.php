<?php

namespace ShortPHP;

trait Database
{
    private $server;
    private $user;
    private $password;
    private $database;
    private $conn;

    public function __construct($database, $server = 'localhost', $user = 'root', $password = '')
    {
        $this->server = $server;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;
    }

    private function newConn()
    {
        $this->conn = new \mysqli($this->server, $this->user, $this->password, $this->database);
        if ($this->conn->error) {
            die("Erro na Conexão");
        }
        return $this->conn;
    }

    private function closeConn()
    {
        if ($this->conn instanceof \mysqli) {
            @$this->conn->close();
        }
    }

    private function search($sql)
    {
        return $this->conn->query($sql);
    }

    private function addItens($result)
    {

        $items = [];
        while ($rows = $result->fetch_assoc()) {
            $items[] = $rows;
        }
        return $items;
    }

    public function returnData($result)
    {
        if ($result != false) {
            if ($result->num_rows) {
                $items = $this->addItens($result);
            }
            if (isset($items)) {
                $this->closeConn();
                return $items;
            } else {
                $this->closeConn();
            }
        } else {
            $result = null;
        }
    }

    public function sendPrepare($sql)
    {
        try {
            $this->conn = $this->newConn();
            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                throw new \Exception("Erro na preparação da declaração: " . $this->conn->error);
            }
            if (!$stmt->execute()) {
                throw new \Exception("Houve um problema durante o envio de dados");
            }
            return true;
        } finally {
            $this->closeConn();
        }
    }

    public function prepare($param, $sql)
    {
        $tipos = [];
        foreach ($param as $i => $items) {
            if (filter_var($items, FILTER_VALIDATE_INT) === true) {
                $tipos[$i] = 'i';
            } else if (filter_var($items, FILTER_VALIDATE_FLOAT) === true) {
                $tipos[$i] = 'd';
            } else if (is_bool($items) === true) {
                $tipos[$i] = 'i';
            } else {
                $tipos[$i] = 's';
            }
        }

        $tipos = implode('', $tipos);

        try {
            $this->conn = $this->newConn();
            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                throw new \Exception("Erro na preparação da declaração: " . $this->conn->error);
            }

            $bindResult = $stmt->bind_param($tipos, ...$param);

            if (!$bindResult || !$stmt->execute()) {
                throw new \Exception("Houve um problema durante o envio de dados. Tipos: $tipos<br>$sql<br>");
            }

            return true;
        } finally {
            $this->closeConn();
        }
    }
    public function selectPrepare($sql, ...$params)
    {
        $tipos = '';
        foreach ($params as $p) {
            if (is_int($p) || is_bool($p)) $tipos .= 'i';
            else if (is_float($p)) $tipos .= 'd';
            else $tipos .= 's';
        }

        try {
            $this->conn = $this->newConn();
            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                throw new \Exception("Erro na preparação da declaração: " . $this->conn->error);
            }

            if (!empty($params)) {
                $stmt->bind_param($tipos, ...$params);
            }

            if (!$stmt->execute()) {
                throw new \Exception("Falha ao executar SELECT preparado");
            }

            $result = $stmt->get_result();
            $items = [];

            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $items[] = $row;
                }
            }

            return $items;
        } finally {
            $this->closeConn();
        }
    }

    public function executePrepare($sql, ...$params)
    {
        $tipos = '';
        foreach ($params as $p) {
            if (is_int($p) || is_bool($p)) $tipos .= 'i';
            else if (is_float($p)) $tipos .= 'd';
            else $tipos .= 's';
        }

        try {
            $this->conn = $this->newConn();
            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                throw new \Exception("Erro na preparação da declaração: " . $this->conn->error);
            }

            if (!empty($params)) {
                $stmt->bind_param($tipos, ...$params);
            }

            return (bool)$stmt->execute();
        } finally {
            $this->closeConn();
        }
    }


    public function deletePrepare($table, $whereSql, ...$params)
    {
        $sql = "DELETE FROM $table $whereSql";
        return $this->executePrepare($sql, ...$params);
    }

    public function select($table, $row = "*", $param = "WHERE 1")
    {
        try {
            $this->conn = $this->newConn();
            $result = $this->search("SELECT $row FROM $table $param");
            return $this->returnData($result);
        } catch (\mysqli_sql_exception $error) {
            return "Ocorreu um erro: " . $error->getMessage();
        }
    }

    public function selectDistinct($table, $row = "*", $param = "WHERE 1")
    {
        try {
            $this->conn = $this->newConn();
            $result = $this->search("SELECT DISTINCT $row FROM $table $param");
            return $this->returnData($result);
        } catch (\mysqli_sql_exception $error) {
            return "Ocorreu um erro: " . $error->getMessage();
        }
    }

    public function merge($tabelas, $linhas, $params = "WHERE 1", $duplicadas = false)
    {
        try {
            $tabs = explode(',', $tabelas);
            $quant = count($tabs);

            if ($duplicadas == false) {
                $duplicadas = "UNION";
            } else {
                $duplicadas = "UNION ALL";
            }

            $sql = [];

            foreach ($tabs as $i => $items) {
                if ($i + 1 == $quant) {
                    $sql[$i] = "SELECT $linhas FROM $items";
                } else {
                    $sql[$i] = "SELECT $linhas FROM $items $duplicadas";
                }
            }
            $sql = implode(' ', $sql);
            $sql = $sql . " $params";
            $this->conn = $this->newConn();
            $result = $this->search($sql);
            return $this->returnData($result);
        } catch (\ERROR $erro) {
            "Houve um erro durante a união das tabelas: " . $erro->getMessage();
        }
    }

    public function write($tabela, $linha, $param = "WHERE 1")
    {
        try {
            $item = $this->select($tabela, $linha, $param);
            if ($item != false) {
                return $item[0][$linha];
            } else {
                return null;
            }
        } catch (\mysqli_sql_exception $error) {
            echo "Ocorreu um erro: " . $error->getMessage();
        }
    }

    public function count($tabela, $param = "WHERE 1")
    {
        try {
            $this->conn = $this->newConn();
            $sql = "SELECT COUNT('id') as $tabela FROM $tabela $param";
            $result = $this->search($sql);
            if ($result != false) {
                if ($result->num_rows) {
                    $items = $this->addItens($result);
                    $this->closeConn();
                    return $items[0][$tabela];
                } else {
                    $this->closeConn();
                    return null;
                }
            }
        } catch (\mysqli_sql_exception $error) {
            echo "Ocorreu um erro: " . $error->getMessage();
        }
    }

    public function insert($tabela, $linhas, ...$infos)
    {
        try {
            $linhas = str_replace(" ", "", $linhas);
            $qntItems = explode(',', $linhas);
            $qntItems = count($qntItems);
            $values = str_repeat('?,', $qntItems - 1) . '?';

            $sql = "INSERT INTO $tabela ($linhas) VALUES ($values)";

            $this->prepare($infos, $sql);

            return true;
        } catch (\Exception $erro) {
            echo "Erro: " . $erro->getMessage();
            echo "<hr>$sql";
        }
    }

    public function update($tabela, $params, $linhas, ...$infos)
    {
        try {
            $linhas = str_replace(" ", "", $linhas);
            $linha = explode(',', $linhas);
            $qntItems = explode(',', $linhas);
            $qntItems = count($qntItems);
            $relacao = [];
            for ($i = 0; $i < $qntItems; $i++) {
                $item = $linha[$i];
                $relacao[$i] = "$item = ?";
            }
            $relacao = implode(',', $relacao);
            $sql = "UPDATE `$tabela` SET $relacao $params";

            $this->prepare($infos, $sql);
            return true;
        } catch (\Exception $erro) {
            echo "Erro: " . $erro->getMessage();
        }
    }



    public function delete($table, $param)
    {
        try {
            $sql = "DELETE FROM $table $param";
            $this->sendPrepare($sql);
        } catch (\Exception $erro) {
            echo "Erro: " . $erro->getMessage();
            return false;
        }
    }

    public function create($nome, ...$params)
    {
        try {
            $this->conn = $this->newConn();
            $colunaDados = implode(',', $params);
            $sql = "CREATE TABLE $nome ($colunaDados)";
            $this->sendPrepare($sql);
            $this->closeConn();
        } catch (\Exception $erro) {
            echo "Erro: " . $erro->getMessage();
            return false;
        }
    }

    static function createDatabase($nome, $entrar = false, $servidor = 'localhost', $usuario = 'root', $senha = '')
    {
        try {
            $sql = "CREATE DATABASE $nome";
            $conn = new \mysqli($servidor, $usuario, $senha);
            if ($conn->connect_error) {
                die("Houve um erro durante a conexão" . $conn->connect_error);
            }
            if ($conn->query($sql) === TRUE) {
                if ($entrar == true) {
                    return new \shortPHP($nome, $servidor, $usuario, $senha);
                } else {
                    return "Banco de dados criado com sucesso";
                }
            }
        } catch (\Error $erro) {
            echo "Houve um erro durante a criação do banco de dados: " . $erro->getMessage();
        } finally {
            $conn->close();
        }
    }

    static function deleteDatabase($nome, $servidor = 'localhost', $usuario = 'root', $senha = '')
    {
        $sql = "DROP DATABASE $nome";
        $conn = new \mysqli($servidor, $usuario, $senha);
        if ($conn->connect_error) {
            die("Houve um erro durante a conexão" . $conn->connect_error);
        }
        if ($conn->query($sql) === TRUE) {
            return "Banco de dados excluído com sucesso!";
        } else {
            return "Erro ao criar o banco de dados: " . $conn->error;
        }
        $conn->close();
    }

    public function add($nome, ...$params)
    {
        try {
            $parametros = implode(', ADD ', $params);
            $sql = "ALTER TABLE $nome ADD $parametros;";
            $this->sendPrepare($sql);
        } catch (\Exception $erro) {
            echo "Erro: " . $erro->getMessage();
            return false;
        }
    }

    public function remove($nome, ...$params)
    {
        try {
            $parametros = implode(', DROP ', $params);
            $sql = "ALTER TABLE $nome DROP $parametros;";
            $this->sendPrepare($sql);
        } catch (\Exception $erro) {
            echo "Erro: " . $erro->getMessage();
            return false;
        }
    }

    public function modify($nome, ...$params)
    {
        try {
            $parametros = implode(', MODIFY ', $params);
            $sql = "ALTER TABLE $nome MODIFY $parametros;";
            $this->sendPrepare($sql);
        } catch (\Exception $erro) {
            echo "Erro: " . $erro->getMessage();
            return false;
        }
    }

    public function status($tabela)
    {
        try {
            $this->conn = $this->newConn();
            $result = $this->search("SHOW TABLE STATUS LIKE '$tabela'");
            return $this->returnData($result)[0];
        } catch (\mysqli_sql_exception $error) {
            echo "Ocorreu um erro: " . $error->getMessage();
        }
    }

    public function createView($nome, $tabela, $colunas, $param = "WHERE 1")
    {
        try {
            $sql = "CREATE VIEW $nome AS SELECT $colunas FROM $tabela $param";
            $this->sendPrepare($sql);
        } catch (\Exception $erro) {
            echo "Erro: " . $erro->getMessage();
            return false;
        }
    }

    public function deleteView($view)
    {
        try {
            $sql = "DROP VIEW $view";
            $this->conn = $this->newConn();
            $this->sendPrepare($sql);
        } catch (\mysqli_sql_exception $error) {
            echo "Ocorreu um erro: " . $error->getMessage();
        }
    }
}
