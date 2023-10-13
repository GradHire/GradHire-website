<?php

namespace app\src\model\Form\fields;

use app\src\model\Form\rules\RuleInArray;
use app\src\model\Form\rules\RuleIsString;

class FormRadio extends AnyAttribute
{
	private array $options;

	public function __construct(string $name, array $options)
	{
		parent::__construct($name);
		$this->options = $options;
		$this->setType(new RuleIsString());
		$this->addRule(new RuleInArray(["allowed" => array_keys($options)]));
	}

	function field(string $name, string $value): string
	{
		$value = $this->getValue($value);
		$select = '';
		foreach ($this->options as $fValue => $label) {
			$id = $fValue . '-' . $name;
			$select .= '<div>
		    <input type="radio" id="' . $id . '" name="' . $name . '" value="' . $fValue . '" ' . ($value === $fValue ? 'checked' : '') . ' ' . ($this->isRequired() ? 'required' : '') . '/>
		    <label for="' . $id . '">' . $label . '</label>
		  </div>';
		}
		return $select;
	}
}