<?php

namespace app\src\model\Form\rules;


use app\src\model\Form\FormInputValue;
use app\src\model\Form\FormValidationException;

class RuleDateBefore extends FormAttributeRule
{

	/**
	 * @inheritDoc
	 */
	public function process(FormInputValue $value): void
	{
		$date = $value->getDate();

		$toCompare = $this->getOption('before');

		if ($date >= $toCompare)
			throw new FormValidationException('Veuillez saisir une date avant le ' . $toCompare->format('d-m-Y'));
	}
}