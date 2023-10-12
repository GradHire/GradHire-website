<?php

namespace app\src\model\Form\fields;


use app\src\model\Form\rules\Modifiers\LowercaseModifier;
use app\src\model\Form\rules\Modifiers\TrimModifier;
use app\src\model\Form\rules\RuleIsString;
use app\src\model\Form\rules\RuleMaxLength;
use app\src\model\Form\rules\RuleMinLength;
use app\src\model\Form\rules\RuleRegex;

class FormString extends FormInputAttribute
{
	public function __construct(string $name)
	{
		parent::__construct($name);
		$this->setType(new RuleIsString());
	}

	public function trim(): static
	{
		$this->addModifier(new TrimModifier());
		return $this;
	}

	public function lowercase(): static
	{
		$this->addModifier(new LowercaseModifier());
		return $this;
	}

	public function min(int $len): static
	{
		$this->addRule(new RuleMinLength(['min' => $len]));
		$this->setParam("minlength", $len);
		return $this;
	}

	public function max(int $len): static
	{
		$this->addRule(new RuleMaxLength(['max' => $len]));
		$this->setParam("maxlength", $len);
		return $this;
	}

	public function numeric(): static
	{
		$this->regex('/^\d+$/', 'Ce champs doit contenir uniquement des chiffres');
		return $this;
	}

	public function regex(string $pattern, string $message = null): static
	{
		$this->addRule(new RuleRegex(["pattern" => $pattern, "message" => $message]));
		return $this;
	}

	protected function getType(): string
	{
		return "text";
	}
}