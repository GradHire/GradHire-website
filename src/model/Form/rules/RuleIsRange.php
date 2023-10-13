<?php

namespace app\src\model\Form\rules;

use app\src\model\Form\FormInputValue;
use app\src\model\Form\FormValidationException;

class RuleIsRange extends FormAttributeRule
{

	/**
	 * @inheritDoc
	 */
	public function process(FormInputValue $value): void
	{
		if (gettype($value->getValue()) !== "string")
			throw new FormValidationException("Veuillez saisir une string");
		$values = explode(',', $value->getValue());
		$useInt = $this->getOption("useInt");

		if (count($values) !== 2)
			throw new FormValidationException("Format invalid");

		$value1 = $useInt ? filter_var($values[0], FILTER_VALIDATE_INT) : filter_var($values[0], FILTER_VALIDATE_FLOAT);
		$value2 = $useInt ? filter_var($values[1], FILTER_VALIDATE_INT) : filter_var($values[1], FILTER_VALIDATE_FLOAT);

		if ($value1 === false || $value2 === false)
			throw new FormValidationException("Veuillez saisir deux nombres");

		if ($value2 < $value1)
			throw new FormValidationException("Max doit être supérieu ou égale à min");

		$value->setValue(["min" => $value1, "max" => $value2]);
	}
}