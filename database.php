<?php trait Database
{

    //BANCO DE DADOS
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

    private function novaConexao()
    // ABRE UMA NOVA CONEXÃO COM O BANCO DE DADOS
    {
        $this->conn = new mysqli($this->server, $this->user, $this->password, $this->database);
        if ($this->conn->error) {
            die("Erro na Conexão");
        }
        return $this->conn;
    }

    private function encerrarConexao()
    // ENCERRA A CONEXÃO
    {
        $this->conn->close();
    }

    private function buscar($sql)
    // FAZ UMA CONSULTA SQL PADRÃO
    {
        return $this->conn->query($sql);
    }

    private function adicionarItens($result)
    // FUNÇÃO USADA PARA TRANSFORMAR AS LINHAS EM UM ARRAY
    {
        while ($rows = $result->fetch_assoc()) {
            $items[] = $rows;
        }
        return $items;
    }

    public function retornarDados($result)
    {
        if ($result->num_rows) {
            $items = $this->adicionarItens($result);
        }
        if (isset($items)) {
            $this->encerrarConexao();
            return $items;
        } else {
            $this->encerrarConexao();
        }
    }

    public function enviarPrepare($sql)
    {
        $this->conn = $this->novaConexao();
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Erro na preparação da declaração: " . $this->conn->error);
        }
        if (!$stmt->execute()) {
            throw new Exception("Houve um problema durante o envio de dados");
        }
        // Se chegou até aqui, a execução foi bem-sucedida
        return true;
    }

    public function preparar($param, $sql)
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

        $this->conn = $this->novaConexao();
        $stmt = $this->conn->prepare($sql); //Preparar sql

        if (!$stmt) {
            throw new Exception("Erro na preparação da declaração: " . $this->conn->error);
        }

        $bindResult = $stmt->bind_param($tipos, ...$param); //RESULTADO

        if (!$bindResult || !$stmt->execute()) {
            throw new Exception("Houve um problema durante o envio de dados. Tipos: $tipos<br>$sql<br>");
        }
    }

    public function selecionar($tabela, $row = "*", $param = "WHERE 1")
    // FAZ UMA CONSULTA NO SQL COM O TÍTULO DO EVENTO E RETORNA UM ARRAY 
    {
        try {
            $this->conn = $this->novaConexao();
            $result = $this->buscar("SELECT $row FROM $tabela $param");
            return $this->retornarDados($result);
        } catch (mysqli_sql_exception $error) {
            echo "Ocorreu um erro: " . $error->getMessage();
        }
    }

    public function unir($tabelas, $linhas, $params = "WHERE 1", $duplicadas = false)
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
            $this->conn = $this->novaConexao();
            $result = $this->buscar($sql);
            return $this->retornarDados($result);
        } catch (ERROR $erro) {
            "Houve um erro durante a união das tabelas: " . $erro->getMessage();
        }
    }

    public function escrever($tabela, $linha, $param = "WHERE 1")
    // FAZ UMA CONSULTA NO SQL E ESCREVE O RESULTADO
    {
        try {
            $item = $this->selecionar($tabela, $linha, $param);
            return $item[0][$linha];
        } catch (mysqli_sql_exception $error) {
            echo "Ocorreu um erro: " . $error->getMessage();
        }
    }

    public function contar($tabela, $param = "WHERE 1")
    // FAZ UMA CONTAGEM E RETORNA A QUANTIDADE DE ITENS DE ACORDO COM OS PARÂMETROS
    {
        try {
            $this->conn = $this->novaConexao();
            $sql = "SELECT COUNT('id') as $tabela FROM $tabela $param"; //código SQL
            $result = $this->buscar($sql); //Enviar requisição
            if ($result->num_rows) {
                //Retorno
                $items = $this->adicionarItens($result);
            }
            $this->encerrarConexao();
            return $items[0][$tabela]; //Retornar resultado
        } catch (mysqli_sql_exception $error) {
            echo "Ocorreu um erro: " . $error->getMessage();
        }
    }

    public function inserir($tabela, $linhas, ...$param)
    // ENVIA OS DADOS PARA O BANCO DE DADOS
    {
        try {
            //Organizar o SQL
            $linhas = str_replace(" ", "", $linhas); // Tirar espaços
            $qntItems = explode(',', $linhas); // Criar array de linhas
            $qntItems = count($qntItems); // Descobrir quantidades
            $values = str_repeat('?,', $qntItems - 1) . '?'; // Adicionar ? do VALUES

            $sql = "INSERT INTO $tabela ($linhas) VALUES ($values)"; // SQL pronto

            $this->preparar($param, $sql); // Chamar função preparar (bind_param);

            return true;
        } catch (Exception $erro) {
            echo "Erro: " . $erro->getMessage();
        } finally {
            $this->encerrarConexao(); //Encerrar conexão
        }
    }

    public function atualizar($tabela, $params, $linhas, ...$infos)
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

            $this->preparar($infos, $sql); // Chamar função preparar (bind_param);
            return true;
        } catch (Exception $erro) {
            echo "Erro: " . $erro->getMessage();
        } finally {
            $this->encerrarConexao(); //Encerrar conexão
        }
    }

    public function deletar($tabela, $param)
    {
        // EXCLUI DADOS DO SERVIDOR COM BASE NO SQL
        try {
            $sql = "DELETE FROM $tabela $param";
            $this->enviarPrepare($sql);
        } catch (Exception $erro) {
            // Captura e lida com exceções
            echo "Erro: " . $erro->getMessage();
            return false;
        } finally {
            // Certifique-se de fechar a conexão, independentemente do resultado
            $this->encerrarConexao(); //Encerrar conexão
        }
    }

    public function criar($nome, ...$params)
    {
        // CRIA UMA TABELA
        try {
            $this->conn = $this->novaConexao();
            $colunaDados = implode(',', $params);
            $sql = "CREATE TABLE $nome ($colunaDados)";
            $this->enviarPrepare($sql);
            $this->encerrarConexao(); //Encerrar conexão
        } catch (Exception $erro) {
            // Captura e lida com exceções
            echo "Erro: " . $erro->getMessage();
            return false;
        }
    }

    static function criarBanco($nome, $entrar = false, $servidor = 'localhost', $usuario = 'root', $senha = '')
    // CRIAR UM BANCO DE DADOS
    {
        try {
            $sql = "CREATE DATABASE $nome";
            $conn = new mysqli($servidor, $usuario, $senha);
            if ($conn->connect_error) {
                die("Houve um erro durante a conexão" . $conn->connect_error);
            }
            if ($conn->query($sql) === TRUE) {
                if ($entrar == true) {
                    return new shortPHP($nome, $servidor, $usuario, $senha);
                } else {
                    return "Banco de dados criado com sucesso";
                }
            }
        } catch (Error $erro) {
            echo "Houve um erro durante a criação do banco de dados: " . $erro->getMessage();
        } finally {
            // Fecha a conexão
            $conn->close();
        }
    }

    static function deletarBanco($nome, $servidor = 'localhost', $usuario = 'root', $senha = '')
    // CRIAR UM BANCO DE DADOS
    {
        $sql = "DROP DATABASE $nome";
        $conn = new mysqli($servidor, $usuario, $senha);
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

    public function adicionar($nome, ...$params)
    {
        //ADICIONA UMA COLUNA NA TABELA
        try {
            $parametros = implode(', ADD ', $params);
            $sql = "ALTER TABLE $nome ADD $parametros;";
            $this->enviarPrepare($sql);
        } catch (Exception $erro) {
            // Captura e lida com exceções
            echo "Erro: " . $erro->getMessage();
            return false;
        } finally {
            // Certifique-se de fechar a conexão, independentemente do resultado
            $this->encerrarConexao(); //Encerrar conexão
        }
    }

    public function remover($nome, ...$params)
    //REMOVE UMA COLUNA COM BASE NOS PARÂMETROS
    {
        try {
            $parametros = implode(', DROP ', $params);
            $sql = "ALTER TABLE $nome DROP $parametros;";
            $this->enviarPrepare($sql);
        } catch (Exception $erro) {
            // Captura e lida com exceções
            echo "Erro: " . $erro->getMessage();
            return false;
        } finally {
            // Certifique-se de fechar a conexão, independentemente do resultado
            $this->encerrarConexao(); //Encerrar conexão
        }
    }

    public function modificar($nome, ...$params)
    //MODIFICA UMA COLUNA 
    {
        try {
            $parametros = implode(', MODIFY ', $params);
            $sql = "ALTER TABLE $nome MODIFY $parametros;";
            $this->enviarPrepare($sql);
        } catch (Exception $erro) {
            // Captura e lida com exceções
            echo "Erro: " . $erro->getMessage();
            return false;
        } finally {
            // Certifique-se de fechar a conexão, independentemente do resultado
            $this->encerrarConexao(); //Encerrar conexão
        }
    }

    public function status($tabela){
        // Mostra informações sobre a tabela
        try {
            $this->conn = $this->novaConexao();
            $result = $this->buscar("SHOW TABLE STATUS LIKE '$tabela'");
            return $this->retornarDados($result)[0];
        } catch (mysqli_sql_exception $error) {
            echo "Ocorreu um erro: " . $error->getMessage();
        }
    }

    public function criarView($nome, $tabela, $colunas, $param = "WHERE 1")
    // CRIAR UMA VIEW
    {
        try {
            $sql = "CREATE VIEW $nome AS SELECT $colunas FROM $tabela $param";
            $this->enviarPrepare($sql);
        } catch (Exception $erro) {
            // Captura e lida com exceções
            echo "Erro: " . $erro->getMessage();
            return false;
        } finally {
            // Certifique-se de fechar a conexão, independentemente do resultado
            $this->encerrarConexao(); //Encerrar conexão
        }
    }

    public function selecionarView($view, $row = "*", $params = 1)
    //SELECIONAR UMA VIEW
    {
        try {
            $this->conn = $this->novaConexao();
            $result = $this->buscar("SELECT $row FROM $view,$params");
            return $this->retornarDados($result);
        } catch (mysqli_sql_exception $error) {
            echo "Ocorreu um erro: " . $error->getMessage();
        }
    }

    static function ajudaDatabase()
    {
        echo "<h3>Funções de Banco de Dados</h3><br>";
        echo '<b>enviar($tabela, $linhas, ...$param)</b>: Envia as informações com base nos parâmetros na função, sendo ela a $tabela, $linhas que serão afetadas e os dados a ser inseridos em ...$param <br>';
        echo '<b>excluir($tabela, $param)</b>: Exclui informações com base nos parâmetros da função <br>';
        echo '<b>selecionar($tabela, $linha, $param)</b>: Retorna um array com as informações pedidas de acordo com os parâmetros<br>';
        echo '<b>escrever($table, $row, $param)</b>: Escreve a informação conforme as instruções pedidas<br>';
        echo '<b>contar($tabela, $param)</b>: Retorna o valor de linhas encontradas de acordo com o parâmetro<br>';
        echo '----------------------<br>';
    }
}
