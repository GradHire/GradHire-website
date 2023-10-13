<?php

namespace app\src\model\Form\fields;


class FormPhone extends FormString
{
	public function __construct(string $name)
	{
		parent::__construct($name);
		// French phone
		$this->regex('/^(?:(?:\+|00)33[\s.-]{0,3}(?:\(0\)[\s.-]{0,3})?|0)[1-9](?:(?:[\s.-]?\d{2}){4}|\d{2}(?:[\s.-]?\d{3}){2})$/', "Veuillez saisir un téléphone valide");
	}

	protected function getType(): string
	{
		return "phone";
	}
}