<?php

namespace app\src\model\Form;

class FormValidationException extends \Exception
{
	public function __toString(): string
	{
		return $this->getMessage();
	}
}