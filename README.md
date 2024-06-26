# shortPHP 1.3 (Em desenvolvimento)
Uma biblioteca para facilitar a conexão com o PHPMyAdmin + utilitários

## Planejamento

 - [x] Adicionar ao composer
 - [x] Aprimorar segurança contra SQL Injection;
 - [x] Comandos **CREATE**, **ALTER**, **DROP**;
 - [x] Criação de **VIEW**;
 - [x] Criar e excluir banco de dados
 - [x] STATUS
 - [x] Envio de Arquivos
 - [x] Sessões


## Inicialização
Adicione no seu composer

    "require":  {  "paulogmello/shortPHP":  "dev-main"  }


## Banco de Dados
Para configurar é simples, você deverá inicializar a conexão instanciando a classe **shortPHP** com os parâmetros do seu banco de dados, que são respectivamente o **Servidor**, **Banco de Dados**, **Usuário** e **Senha**.
Exemplo:

    $meusCarros = new shortPHP("lojaDeCarro", "localhost", "root", "12345");

### Comandos disponíveis
Utilize os comandos para criar, consultar, modificar ou deletar conforme a sua necessidade:
|Comando| Resultado |
|--|--|
| criarBanco| Cria um banco de dados |
| deletarBanco| Deleta um banco de dados |
| criar| Cria uma tabela |
| adicionar| Adiciona uma coluna em uma tabela |
| remover| Remove uma coluna em uma tabela |
| modificar| Modifica o tipo da estrutura de uma coluna |
| inserir| Insere linhas em uma tabela |
| atualizar| Atualiza uma linha em uma tabela |
| deletar| Exclui linhas em uma tabela |
| selecionar | Retorna um array com base nos parâmetros SQL |
| escrever| Escreve o conteúdo com base nos parâmetros SQL |
| contar| Escreve a quantidade de linhas encontradas com base nos parâmetros SQL|
| unir| Retorna os dados de 2 ou mais tabelas de acordo com parâmetros|
| criarView| Cria uma view com base nos parâmetros |
| selecionarView| retorna os dados de uma view |
| enviarArquivo| Envia um arquivo para um diretório |
| escreverArquivo| Escreve um novo arquivo com base nos parâmetros |
| deletarArquivo| Deleta um arquivo |
| criarDiretorio| Cria uma nova pasta |
| converter| Converte a extensão de um arquivo (requer Imagick) |

Para mais exemplos de funções, verifique a documentação

Exemplo:

    $meusCarros = new  shortPHP('lojaDeCarros');
    $meusCarros->criar('carros', 'id INT AUTO_INCREMENT', 'marca TEXT', 'modelo TEXT', 'valor FLOAT', 'placa TEXT', 'PRIMARY KEY(id)');
    $marca = "Toyota";
    $modelo = "Corolla XEI";
    $valor = "119900";
    $placa = "ABC-123";
    $meusCarros->inserir('carros', 'marca, modelo, valor, placa',
    $marca, $modelo, $valor, $placa);
    $todosOsCarros = $meusCarros->selecionar('carros');
    
    // Retorna ( [0] => Array ( [id] => 1 [marca] => Toyota [modelo] => Corolla XEI [valor] => 119900 [placa] => ABC-123 ));

  ## Funções Utilitárias
O shortPHP tem várias funções para ajudar a facilitar o trabalho, para usa-las, siga o exemplo:

    $minhaMediaEscolar = shortPHP::media(10,8,6,8); retorna 8
    $valores = shortPHP::arredondamento(5.6, 8.2, 84); retorna Array ( [0] => 6 [1] => 8 [2] => 84 )

#### Exemplo de funções
Novas funções são adicionadas a cada atualização

| Função | Resultado |
|--|--|
| ajuda | Mostra na tela todos os componentes do shortPHP |
 numerico | Retorna true caso seja um número |
| dados | Recebe o mínimo e o máximo respectivamente e retorna um número aleatório entre eles |
| arredondar | Recebe um número e arredonda ele|
| arredondamento| Recebe um array e retorna outro com todos os números arredondados |
| media | Recebe um array com vários números e retorna a média aritimética simples deles|
| arrayJavascript | Converte um array PHP para um array Javascript |
