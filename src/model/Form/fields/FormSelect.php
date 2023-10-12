<?php

namespace app\src\model\Form\fields;


use app\src\model\Form\rules\RuleInArray;
use app\src\model\Form\rules\RuleIsString;

class FormSelect extends AnyAttribute
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
		$select = '<select name="' . $name . '" ' . $this->getParams() . '>';
		foreach ($this->options as $fValue => $label) {
			$select .= "<option value=\"$fValue\" " . ($value === $fValue ? 'selected="selected"' : '') . " >$label</option>";
		}
		$select .= "</select>";
		return $select;
	}
}