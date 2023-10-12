<?php

namespace app\src\model\Form\rules;


use app\src\model\Form\FormInputValue;
use app\src\model\Form\FormValidationException;

class RuleMatch extends FormAttributeRule
{

	/**
	 * @inheritDoc
	 */
	public function process(FormInputValue $value): void
	{
		$toMatch = $this->getOption("match");
		$field = $value->getField($toMatch);
		if (is_null($field))
			throw new FormValidationException("L'attribut " . $toMatch . " n'existe pas");
		$matchValue = $value->getBodyValue($toMatch);
		if (is_null($matchValue))
			throw new FormValidationException("Veuillez remplir le champs '" . $field->getName() . "'");
		if ($matchValue != $value->getValue())
			throw new FormValidationException("Veuillez saisir la même valeur que le champs '" . $field->getName() . "'");
	}
}