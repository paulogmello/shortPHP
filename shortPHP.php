<?php

/**
 * shortPHP - A library to improve your work with PHP, making SQL connections easily and faster, simplifying the process and speed up your project
 *
 * @see       https://github.com/paulogmello/shortPHP lib in github
 * @see       https://www.paulogmello/projects/shortPHP
 * @author    Paulo Guilherme de Mello <paulogmello.com>
 * @version   1.5
 */

// Import files
require_once 'array.php';
require_once 'basic.php';
require_once 'database.php';
require_once 'files.php';
require_once 'mail.php';
require_once 'math.php';
require_once 'sessions.php';

/** Main class of the library
 *  Don't change anything if you don't know how;
 */

class shortPHP
{
    use ShortPHP\Basic; // Basic functions
    use ShortPHP\Database; // Database functions
    use ShortPHP\fArray; // Arrays functions;
    use ShortPHP\Files; // File management functions
    use ShortPHP\Mail; // Mails functions
    use ShortPHP\Math; // Math functions
    use ShortPHP\Sessions; // Sessions functions

    static function version()
    {
        echo ("shortPHP 1.5");
    }
}
