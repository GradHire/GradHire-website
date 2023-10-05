<?php

namespace app\src\core\exception;

class ServerErrorException extends \Exception
{
    protected $message = 'Server error';
    protected $code = 500;
}