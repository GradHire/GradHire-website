<?php

namespace app\src\model\Form\fields;

use app\src\model\Form\rules\RuleRequired;

class FormFile extends FormAttribute
{
	function field(string $name, string $value): string
	{
		return '<input type="file" name="' . $name . '" ' . $this->getParams() . '/>';
	}

	public function required(): static
	{
		$this->addRule(new RuleRequired());
		return $this;
	}
}