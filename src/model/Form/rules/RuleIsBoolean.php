<?php

namespace app\src\model\Form\rules;


use app\src\model\Form\FormInputValue;

class RuleIsBoolean extends FormAttributeRule
{

	/**
	 * @inheritDoc
	 */
	public function process(FormInputValue $value): void
	{
		$value->setValue($value->getValue() === "on");
	}
}