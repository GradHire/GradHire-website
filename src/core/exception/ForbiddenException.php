<?php

namespace app\src\core\exception;

class ForbiddenException extends \Exception
{
	public string $title = "Qui êtes vous ?";
	protected $message = "Il s'emblerait que vous n'ayez pas la permission d'accéder à cette page.";
	protected $code = 403;
}