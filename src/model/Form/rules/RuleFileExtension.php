<?php

namespace app\src\model\Form\rules;

use app\src\model\Form\FormInputValue;
use app\src\model\Form\FormValidationException;

class RuleFileExtension extends FormAttributeRule
{

	/**
	 * @inheritDoc
	 */
	public function process(FormInputValue $value): void
	{
		if (is_null($value->getValue())) return;
		$fileName = $value->getValue()['name'];
		$extension = '.' . pathinfo($fileName, PATHINFO_EXTENSION);
		if (!in_array($extension, $this->getOption("extensions")))
			throw new FormValidationException("L'extension " . $extension . " n'est pas valide");
	}
}