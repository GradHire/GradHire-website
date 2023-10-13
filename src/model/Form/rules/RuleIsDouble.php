<?php

namespace app\src\model\Form\rules;


use app\src\model\Form\FormInputValue;
use app\src\model\Form\FormValidationException;

class RuleIsDouble extends FormAttributeRule
{

	/**
	 * @inheritDoc
	 */
	public function process(FormInputValue $value): void
	{
		if (!is_double($value->getValue()))
			throw new FormValidationException("Ce champs n'est pas une double");
	}
}