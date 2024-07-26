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
    // FUNÇÃO CONSTRUTORA DA CLASSE
    {
        $this->server = $server;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;
    }

    private function newConn()
    // ABRE UMA NOVA CONEXÃO COM O BANCO DE DADOS
    {
        $this->conn = new \mysqli($this->server, $this->user, $this->password, $this->database);
        if ($this->conn->error) {
            die("Erro na Conexão");
        }
        return $this->conn;
    }

    private function closeConn()
    // ENCERRA A CONEXÃO
    {
        $this->conn->close();
    }

    private function search($sql)
    // FAZ UMA CONSULTA SQL PADRÃO
    {
        return $this->conn->query($sql);
    }

    private function addItens($result)
    // FUNÇÃO USADA PARA TRANSFORMAR AS LINHAS EM UM ARRAY
    {
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
        $this->conn = $this->newConn();
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new \Exception("Erro na preparação da declaração: " . $this->conn->error);
        }
        if (!$stmt->execute()) {
            throw new \Exception("Houve um problema durante o envio de dados");
        }
        // Se chegou até aqui, a execução foi bem-sucedida
        return true;
    }

    public function prepare($param, $sql)
    {
        //PREPARA O SQL PARA SER ENVIADO
        $tipos = []; // tipos para o bind_param baseado no foreach abaixo
        foreach ($param as $i => $items) {
            if (filter_var($items, FILTER_VALIDATE_INT) == true) {
                // Verificar se é INT
                $tipos[$i] = 'i';
            } else if (filter_var($items, FILTER_VALIDATE_FLOAT) == true) {
                // Verificar se é FLOAT
                $tipos[$i] = 'd';
            } else if (is_string($items) == true) {
                // Verificar se é STRING
                $tipos[$i] = 's';
            } else {
                // Deve ser BOB;
                $tipos[$i] = 'b';
            }
        }

        $tipos = implode('', $tipos); //Refatora os tipos do array

        $this->conn = $this->newConn();
        $stmt = $this->conn->prepare($sql); //Preparar sql

        if (!$stmt) {
            throw new \Exception("Erro na preparação da declaração: " . $this->conn->error);
        }

        $bindResult = $stmt->bind_param($tipos, ...$param); //RESULTADO

        if (!$bindResult || !$stmt->execute()) {
            throw new \Exception("Houve um problema durante o envio de dados. Tipos: $tipos<br>$sql<br>");
        }
    }

    public function select($table, $row = "*", $param = "WHERE 1")
    // FAZ UMA CONSULTA NO SQL COM O TÍTULO DO EVENTO E RETORNA UM ARRAY 
    {
        try {
            $this->conn = $this->newConn();
            $result = $this->search("SELECT $row FROM $table $param");
            return $this->returnData($result);
        } catch (\mysqli_sql_exception $error) {
            echo "Ocorreu um erro: " . $error->getMessage();
        }
    }

    public function merge($tabelas, $linhas, $params = "WHERE 1", $duplicadas = false)
    // UNE DUAS OU MAIS TABELAS ATRAVÉS DO SELECT
    {
        try {
            $tabs = explode(',', $tabelas); //Transformar tabelas em array
            $quant = count($tabs); //Quantidade de tabelas

            //Verificação se terão duplicadas
            if ($duplicadas == false) {
                $duplicadas = "UNION";
            } else {
                $duplicadas = "UNION ALL";
            }

            $sql = []; //Sql parâmetro

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
    // FAZ UMA CONSULTA NO SQL E ESCREVE O RESULTADO
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
    // FAZ UMA CONTAGEM E RETORNA A QUANTIDADE DE ITENS DE ACORDO COM OS PARÂMETROS
    {
        try {
            $this->conn = $this->newConn();
            $sql = "SELECT COUNT('id') as $tabela FROM $tabela $param"; //código SQL
            $result = $this->search($sql); //Enviar requisição
            if ($result != false) {
                if ($result->num_rows) {
                    //Retorno
                    $items = $this->addItens($result);
                    $this->closeConn();
                    return $items[0][$tabela]; //Retornar resultado
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
    // ENVIA OS DADOS PARA O BANCO DE DADOS
    {
        try {
            //Organizar o SQL
            $linhas = str_replace(" ", "", $linhas); // Tirar espaços
            $qntItems = explode(',', $linhas); // Criar array de linhas
            $qntItems = count($qntItems); // Descobrir quantidades
            $values = str_repeat('?,', $qntItems - 1) . '?'; // Adicionar ? do VALUES

            $sql = "INSERT INTO $tabela ($linhas) VALUES ($values)"; // SQL pronto

            $this->prepare($infos, $sql); // Chamar função preparar (bind_param);

            return true;
        } catch (\Exception $erro) {
            echo "Erro: " . $erro->getMessage();
        } finally {
            $this->closeConn(); //Encerrar conexão
        }
    }

    public function update($tabela, $params, $linhas, ...$infos)
    {
        //ATUALIZAR DADOS DO BANCO DE DADOS
        try {
            $linhas = str_replace(" ", "", $linhas); // Tirar espaços
            $linha = explode(',', $linhas);
            $qntItems = explode(',', $linhas); // Criar array de linhas
            $qntItems = count($qntItems); // Descobrir quantidades
            $relacao = []; // Array para abrigar o SQL alterado
            for ($i = 0; $i < $qntItems; $i++) {
                // Organizar o SQL alterado
                $item = $linha[$i];
                $relacao[$i] = "$item = ?";
            }
            $relacao = implode(',', $relacao); // Transformar em string
            $sql = "UPDATE `$tabela` SET $relacao $params"; // SQL pronto

            $this->prepare($infos, $sql); // Chamar função preparar (bind_param);
            return true;
        } catch (\Exception $erro) {
            echo "Erro: " . $erro->getMessage();
        } finally {
            $this->closeConn(); //Encerrar conexão
        }
    }

    public function delete($table, $param)
    {
        // EXCLUI DADOS DO SERVIDOR COM BASE NO SQL
        try {
            $sql = "DELETE FROM $table $param";
            $this->sendPrepare($sql);
        } catch (\Exception $erro) {
            // Captura e lida com exceções
            echo "Erro: " . $erro->getMessage();
            return false;
        } finally {
            // Certifique-se de fechar a conexão, independentemente do resultado
            $this->closeConn(); //Encerrar conexão
        }
    }

    public function create($nome, ...$params)
    {
        // CRIA UMA TABELA
        try {
            $this->conn = $this->newConn();
            $colunaDados = implode(',', $params);
            $sql = "CREATE TABLE $nome ($colunaDados)";
            $this->sendPrepare($sql);
            $this->closeConn(); //Encerrar conexão
        } catch (\Exception $erro) {
            // Captura e lida com exceções
            echo "Erro: " . $erro->getMessage();
            return false;
        }
    }

    static function createDatabase($nome, $entrar = false, $servidor = 'localhost', $usuario = 'root', $senha = '')
    // CRIAR UM BANCO DE DADOS
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
            // Fecha a conexão
            $conn->close();
        }
    }

    static function deleteDatabase($nome, $servidor = 'localhost', $usuario = 'root', $senha = '')
    // CRIAR UM BANCO DE DADOS
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

        // Fecha a conexão
        $conn->close();
    }

    public function add($nome, ...$params)
    {
        //ADICIONA UMA COLUNA NA TABELA
        try {
            $parametros = implode(', ADD ', $params);
            $sql = "ALTER TABLE $nome ADD $parametros;";
            $this->sendPrepare($sql);
        } catch (\Exception $erro) {
            // Captura e lida com exceções
            echo "Erro: " . $erro->getMessage();
            return false;
        } finally {
            // Certifique-se de fechar a conexão, independentemente do resultado
            $this->closeConn(); //Encerrar conexão
        }
    }

    public function remove($nome, ...$params)
    //REMOVE UMA COLUNA COM BASE NOS PARÂMETROS
    {
        try {
            $parametros = implode(', DROP ', $params);
            $sql = "ALTER TABLE $nome DROP $parametros;";
            $this->sendPrepare($sql);
        } catch (\Exception $erro) {
            // Captura e lida com exceções
            echo "Erro: " . $erro->getMessage();
            return false;
        } finally {
            // Certifique-se de fechar a conexão, independentemente do resultado
            $this->closeConn(); //Encerrar conexão
        }
    }

    public function modify($nome, ...$params)
    //MODIFICA UMA COLUNA 
    {
        try {
            $parametros = implode(', MODIFY ', $params);
            $sql = "ALTER TABLE $nome MODIFY $parametros;";
            $this->sendPrepare($sql);
        } catch (\Exception $erro) {
            // Captura e lida com exceções
            echo "Erro: " . $erro->getMessage();
            return false;
        } finally {
            // Certifique-se de fechar a conexão, independentemente do resultado
            $this->closeConn(); //Encerrar conexão
        }
    }

    public function status($tabela)
    {
        // Mostra informações sobre a tabela
        try {
            $this->conn = $this->newConn();
            $result = $this->search("SHOW TABLE STATUS LIKE '$tabela'");
            return $this->returnData($result)[0];
        } catch (\mysqli_sql_exception $error) {
            echo "Ocorreu um erro: " . $error->getMessage();
        }
    }

    public function createView($nome, $tabela, $colunas, $param = "WHERE 1")
    // CRIAR UMA VIEW
    {
        try {
            $sql = "CREATE VIEW $nome AS SELECT $colunas FROM $tabela $param";
            $this->sendPrepare($sql);
        } catch (\Exception $erro) {
            // Captura e lida com exceções
            echo "Erro: " . $erro->getMessage();
            return false;
        } finally {
            // Certifique-se de fechar a conexão, independentemente do resultado
            $this->closeConn(); //Encerrar conexão
        }
    }

    public function deleteView($view)
    // DELETA UMA VIEW
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
