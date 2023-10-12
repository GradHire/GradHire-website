<?php

namespace app\src\model\Form\rules;


use app\src\model\Form\FormInputValue;
use app\src\model\Form\FormValidationException;

class RuleMinNumber extends FormAttributeRule
{
	public function process(FormInputValue $value): void
	{
		$number = $this->getOption('min');
		if ($value->getValue() < $number)
			throw new FormValidationException(sprintf('Ce champs doit être >= %d', $number));
	}
}