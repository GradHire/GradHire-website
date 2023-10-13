<?php

namespace app\src\model\Form\fields;


use app\src\model\Form\rules\RuleMatch;

abstract class AnyAttribute extends FormAttribute
{
	protected bool $forget = false;
	protected mixed $default = null;

	public function match(string $field): static
	{
		$this->addRule(new RuleMatch(["match" => $field]));
		return $this;
	}

	public function forget(): static
	{
		$this->forget = true;
		return $this;
	}

	public function default($value): static
	{
		$this->default = $value;
		return $this;
	}


	public function isRequired(): bool
	{
		return in_array("required", $this->params);
	}
}