<?php

namespace app\src\model\Form\fields;

use app\src\model\Form\rules\RuleIsBoolean;

class FormCheckbox extends AnyAttribute
{
	public function __construct(string $name)
	{
		parent::__construct($name);
		$this->setType(new RuleIsBoolean());
	}


	/**
	 * @param bool $value
	 * @return $this
	 */
	public function default($value): static
	{
		$this->default = $value ? "true" : "";
		return $this;
	}

	function field(string $name, string $value): string
	{
		$value = $this->getValue($value);
		return '<input type="checkbox" name="' . $name . '" ' . ($value === "true" ? "checked" : '') . $this->getParams() . ' />';
	}

	protected function getType(): string
	{
		return "checkbox";
	}


}