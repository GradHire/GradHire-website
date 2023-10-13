<?php

namespace app\src\core\exception;

class ServerErrorException extends \Exception
{
	public string $title = "Oups petit soucis technique.";
	protected $message = 'Il semblerait que le serveur à rencontrer un problème. Veuillez réessayer plus tard.';
	protected $code = 500;
}