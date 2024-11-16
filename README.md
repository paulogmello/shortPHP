# ShortPHP 1.5
This library is for you who want use your time developing the system, pages, a lot of cool things and has no time to think about connections, databases, etc.

With shortPHP, you use a few line of codes (one in most cases) to **select, update, insert, delete in databases, send and delete files, and many more**.

We have some modules in shortPHP, like:
| Module | Purpouse |
|--|--|
| Database | Select, Update, Insert, Delete and another SQL Commands |
| Files | Read, Create, Send, Delete Files |
| Array | Tools to improve your arrays |
| Basic | A few tools like date, criptography and more |
| Mail | Send e-mails |
| Sessions | Create and organize Sessions and Cookies |
| Math | Math operations |

### Instalation
The most faster way to install is in composer, but you can donwload from github

    composer require paulogmello/short-php:dev-main

### Examples

    $db = new shortPHP('myCars', $server, $user, $pass);
    
    $database->insert('cars', 'name,value,type,color', $name, $value,'sedan', 1);
    $cars_red = $database->select('cars', '*', 'WHERE color = 1');
