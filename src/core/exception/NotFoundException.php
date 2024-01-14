<?php

namespace app\src\core\exception;

use Exception;

class NotFoundException extends Exception
{
    public string $title = "La page que vous cherchez n'existe pas.";
    protected $message = "Désolé, nous n'avons pas pu trouver la page que vous cherchez.";
    protected $code = 404;

}