<?php

namespace app\src\model\Form\rules;

use app\src\model\Form\FormInputValue;
use app\src\model\Form\FormValidationException;

class RuleIsArray extends FormAttributeRule
{

	/**
	 * @inheritDoc
	 */
	public function process(FormInputValue $value): void
	{
		if (is_null($value->getValue())) {
			$value->setValue([]);
			return;
		}
		if (!is_array($value->getValue()))
			throw new FormValidationException("Ce champs doit être sous la forme d'une array");
	}
}