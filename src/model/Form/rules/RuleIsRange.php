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
		$values = $value->getValue();
		if (!is_array($values))
			throw new FormValidationException("Ce champs doit être sous la forme d'une array");
		if (count($values) !== 2)
			throw new FormValidationException("Taille invalide");

		$useInt = $this->getOption("useInt");
		$value1 = $useInt ? filter_var($values[0], FILTER_VALIDATE_INT) : filter_var($values[0], FILTER_VALIDATE_FLOAT);
		$value2 = $useInt ? filter_var($values[1], FILTER_VALIDATE_INT) : filter_var($values[1], FILTER_VALIDATE_FLOAT);

		if ($value1 === false || $value2 === false)
			throw new FormValidationException("Veuillez saisir deux nombres");

		if ($value2 < $value1)
			throw new FormValidationException("Max doit être supérieu ou égale à min");

		$value->setValue(["min" => $value1, "max" => $value2]);
	}
}