<?php

namespace app\src\model\Form\fields;


class FormPassword extends FormString
{
	public function __construct(string $name)
	{
		parent::__construct($name);
		$this->forget();
	}

	protected function getType(): string
	{
		return "password";
	}
}