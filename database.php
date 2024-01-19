<?php trait Database
{

    //BANCO DE DADOS
    private $server;
    private $user;
    private $password;
    private $database;
    private $conn;

    public function __construct($server,  $database, $user, $password)
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

    public function selecionar($tabela, $row, $param = 1)
    // FAZ UMA CONSULTA NO SQL COM O TÍTULO DO EVENTO E RETORNA UM ARRAY 
    {
        try {
            $this->conn = $this->novaConexao();
            $result = $this->buscar("SELECT $row FROM $tabela WHERE $param");
            
            if ($result->num_rows) {
                $items = $this->adicionarItens($result);
            }
            if (isset($items)) {
                $this->encerrarConexao();
                return $items;
            } else {
                $this->encerrarConexao();
            }
        } catch (mysqli_sql_exception) {
            // Captura a exceção e evita que o erro seja mostrado
        }
    }

    public function escrever($table, $row, $param = 1)
    // FAZ UMA CONSULTA NO SQL E ESCREVE O RESULTADO
    {
        try {
            $this->conn = $this->novaConexao();
            $result = $this->buscar("SELECT $row FROM $table WHERE $param");
            if ($result->num_rows) {
                $items = $this->adicionarItens($result);
            }
            foreach ($items as $item) : endforeach;
            $this->encerrarConexao();
            return $item[$row];
        } catch (mysqli_sql_exception) {
            // Captura a exceção e evita que o erro seja mostrado
        }
    }

    public function contar($tabela, $param = 1)
    // FAZ UMA CONTAGEM E RETORNA A QUANTIDADE DE ITENS DE ACORDO COM OS PARÂMETROS
    {
        try {
            $this->conn = $this->novaConexao();
            $sql = "SELECT COUNT('id') as $tabela FROM $tabela WHERE $param";
            // echo $sql;
            $result = $this->buscar($sql);
            if ($result->num_rows) {
                $items = $this->adicionarItens($result);
            }
            foreach ($items as $item) : endforeach;
            $this->encerrarConexao();
            return $item[$tabela];
        } catch (mysqli_sql_exception) {
            // Captura a exceção e evita que o erro seja mostrado
        }
    }

    public function enviar($tabela, $linhas, ...$param)
    // ENVIA OS DADOS PARA O BANCO DE DADOS
    {
        try {
            //Organizar o SQL
            $qntItems = explode(',', $linhas);
            $qntItems = count($qntItems);
            $values = str_repeat('?,', $qntItems - 1) . '?';
            
            $sql = "INSERT INTO $tabela ($linhas) VALUES ($values)"; // SQL pronto

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

            $bindResult = $stmt->bind_param($tipos, ...$param);

            if (!$bindResult || !$stmt->execute()) {
                throw new Exception("Houve um problema durante o envio de dados. Tipos: $tipos");
            }

            // Se chegou até aqui, a execução foi bem-sucedida
            return true;
        } catch (Exception $erro) {
            echo "Erro: " . $erro->getMessage();
        } finally {
            $this->encerrarConexao(); //Encerrar conexão
        }
    }

    public function excluir($tabela, $param)
    {
        // EXCLUI DADOS DO SERVIDOR COM BASE NO SQL
        try {
            $sql = "DELETE FROM $tabela WHERE $param";
            $this->conn = $this->novaConexao();
            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Erro na preparação da declaração: " . $this->conn->error);
            }

            if (!$stmt->execute()) {
                throw new Exception("Houve um problema durante a exclusão de dados.");
            }

            // Se chegou até aqui, a execução foi bem-sucedida
            return true;
        } catch (Exception $erro) {
            // Captura e lida com exceções
            echo "Erro: " . $erro->getMessage();
            return false;
        } finally {
            // Certifique-se de fechar a conexão, independentemente do resultado
            $this->encerrarConexao(); //Encerrar conexão
        }
    }

    static function ajudaDatabase(){
        echo "<h3>Funções de Banco de Dados</h3><br>";
        echo '<b>enviar($tabela, $linhas, ...$param)</b>: Envia as informações com base nos parâmetros na função, sendo ela a $tabela, $linhas que serão afetadas e os dados a ser inseridos em ...$param <br>';
        echo '<b>excluir($tabela, $param)</b>: Exclui informações com base nos parâmetros da função <br>';
        echo '<b>selecionar($tabela, $linha, $param)</b>: Retorna um array com as informações pedidas de acordo com os parâmetros<br>';
        echo '<b>escrever($table, $row, $param)</b>: Escreve a informação conforme as instruções pedidas<br>';
        echo '<b>contar($tabela, $param)</b>: Retorna o valor de linhas encontradas de acordo com o parâmetro<br>';
        echo '----------------------<br>';
    }
}