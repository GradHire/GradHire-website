<?php

namespace app\src\model\Form\fields;


use app\src\model\Form\rules\RuleMaxNumber;
use app\src\model\Form\rules\RuleMinNumber;

class FormNumberInput extends FormInputAttribute
{
	public function min(int $value): static
	{
		$this->addRule(new RuleMinNumber(['min' => $value]));
		return $this;
	}

	public function max(int $value): static
	{
		$this->addRule(new RuleMaxNumber(['max' => $value]));
		return $this;
	}

	protected function getType(): string
	{
		return "number";
	}
}