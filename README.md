# ShortPHP 1.0 (Em desenvolvimento)
Um simples Framework PHP para agilizar conexões com PHPMyAdmin e outros utilitários.

## Banco de Dados
Para configurar é simples, você deverá inicializar a conexão instanciando a classe Conn com os parâmetros do seu banco de dados, que são respectivamente o **Servidor**, **Banco de Dados**, **Usuário** e **Senha**.
Exemplo:

    $meusCarros = new shortPHP("localhost", "carros", "root", "12345");

|Comando| Resultado |
|--|--|
| enviar| Envia dados com base nos parâmetros SQL |
| excluir| Exclui dados com base nos parâmetros SQL |
| selecionar | Retorna um array com base nos parâmetros SQL |
| escrever| Escreve o conteúdo com base nos parâmetros SQL |
| contar| Escreve a quantidade de linhas encontradas com base nos parâmetros SQL|



Exemplos:

    //Enviará dados para o banco de dados
    $marca = "Toyota";
    $modelo = "Corolla XEI";
    $valor = "119900";
    $placa = "ABC-123";
    $meusCarros->enviar('carros', 'marca, modelo, valor, placa', $marca, $modelo, $valor, $placa);
    
    // Excluirá dados do banco de dados
    $meusCarros->excluir("carros", "marca = 'fiat'");
    
    // Retornará um array com apenas carros da marca Fiat
    $fiat = $meusCarros->selecionar("carros", "*" , "carros = 'fiat'");
    
    // Retornará uma string com o valor baseado no parâmetro
    $facebook = $meusCarros->escrever("socialmedia", "link", "titulo = 'facebook'");
    
    // Retornará a quantidade de linhas de acordo com o parâmetro
    $qntCarros = $meusCarros->contar('carros');
    $qntCarrosRenault = $meusCarros->contar("carros", "marca = 'Renault'");
    


  ## Funções Utilitárias
O shortPHP tem várias funções para ajudar a facilitar o trabalho, para usa-las, siga o exemplo:

    $minhaMediaEscolar = shortPHP::media(10,8,6,8); retorna 8
    $valores = shortPHP::arredondamento(5.6, 8.2, 84); retorna Array ( [0] => 6 [1] => 8 [2] => 84 ) 
O código acima irá retornar o valor da média aritmética simples com os valores passados na função.