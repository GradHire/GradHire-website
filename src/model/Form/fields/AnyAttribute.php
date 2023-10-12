<?php

namespace app\src\model\Form\fields;


use app\src\model\Form\rules\RuleMatch;
use app\src\model\Form\rules\RuleRequired;

abstract class AnyAttribute extends FormAttribute
{
	protected bool $forget = false;
	protected mixed $default = null;

	public function match(string $field): static
	{
		$this->addRule(new RuleMatch(["match" => $field]));
		return $this;
	}

	public function required(): static
	{
		$this->addPriorityRule(new RuleRequired());
		$this->setParam("required");
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

	public function id(string $id): static
	{
		$this->setParam("id", $id);
		return $this;
	}

	public function params(array $attributes): static
	{
		foreach ($attributes as $key => $value)
			if (is_null($value))
				$this->setParam($key);
			else
				$this->setParam($key, $value);
		return $this;
	}
}