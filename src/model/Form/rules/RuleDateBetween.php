<?php

namespace app\src\model\Form\rules;


use app\src\model\Form\FormInputValue;
use app\src\model\Form\FormValidationException;

class RuleDateBetween extends FormAttributeRule
{

	/**
	 * @inheritDoc
	 */
	public function process(FormInputValue $value): void
	{
		$date = $value->getDate();

		$before = $this->getOption('before');
		$after = $this->getOption('after');


		if ($date >= $before && $date <= $after) return;
		throw new FormValidationException('Veuillez saisir une date entre le ' . $before->format('d-m-Y') . ' et le ' . $after->format('d-m-Y'));
	}
}