<?php

namespace app\src\model\Form\rules;


use app\src\model\Form\FormInputValue;
use app\src\model\Form\FormValidationException;

class RuleIsInt extends FormAttributeRule
{

	/**
	 * @inheritDoc
	 */
	public function process(FormInputValue $value): void
	{
		if (!is_int($value->getValue()))
			throw new FormValidationException("Ce champs n'est pas une int");
	}
}