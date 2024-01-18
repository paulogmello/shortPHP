# ShortPHP
Um simples Framework PHP para conexões simples com PHPMyAdmin e outros utilitários


### Conexões
As conexões são feitas através da variável **$shortConn**, a partir dela utilize os comandos **selecionar**, **escrever** ou **contar** para retornar as seguintes funcionalidades;
|Comando| Resultado |
|--|--|
| selecionar | Retorna um array com base nos parâmetros |
| escrever| Escreve o conteúdo com base nos parâmetros |
| contar| Escreve a quantidade de linhas encontradas com base nos parâmetros |

Exemplos:

    // Irá retornar um array com apenas carros da marca Fiat
    $fiat = $shortConn->selecionar("carros", "*" "WHERE carros = 'fiat'");
    
    // Retornará uma string com o valor baseado no parâmetro
    $facebook = $shortConn->escrever("socialmedia", "link", "WHERE titulo = 'facebook'";
    
    

