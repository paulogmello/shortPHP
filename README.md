# ShortPHP 1.0 (Em desenvolvimento)
Um simples Framework PHP para conexões simples com PHPMyAdmin e outros utilitários.

## Banco de Dados
Para configurar é simples, você deverá inicializar a conexão instanciando a classe Conn com os parâmetros do seu banco de dados, que são respectivamente o **Servidor**, **Banco de Dados**, **Usuário** e **Senha**.
Exemplo:

    $meusCarros = new shortPHP("localhost", "carros", "root", "12345");

|Comando| Resultado |
|--|--|
| selecionar | Retorna um array com base nos parâmetros |
| escrever| Escreve o conteúdo com base nos parâmetros |
| contar| Escreve a quantidade de linhas encontradas com base nos parâmetros |

Além disso, o usuário precisa passar 3 parâmetros para a função, sendo respectivamente a **tabela**, **linhas** e **parâmetros**.
Exemplos:

    // Irá retornar um array com apenas carros da marca Fiat
    $fiat = $meusCarros->selecionar("carros", "*" "WHERE carros = 'fiat'");
    
    // Retornará uma string com o valor baseado no parâmetro
    $facebook = $meusCarros->escrever("socialmedia", "link", "WHERE titulo = 'facebook'";
    
  ## Funções Utilitárias
O shortPHP tem várias funções para ajudar a facilitar o trabalho, para usa-las, siga o exemplo:

    $minhaMediaEscolar = shortPHP::media(10,8,6,8);
    echo $minhaMediaEscolar;
O código acima irá retornar o valor da média aritmética simples com os valores passados na função.