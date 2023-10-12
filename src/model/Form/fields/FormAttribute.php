<?php

namespace app\src\model\Form\fields;


use app\src\model\Form\FormInputValue;
use app\src\model\Form\FormValidationException;
use app\src\model\Form\rules\FormAttributeRule;

abstract class FormAttribute
{
	protected string $name;
	protected bool $forget = false;
	protected mixed $default;
	protected array $params = [];
	/**
	 * @var FormAttributeRule[]
	 */
	private array $priority_rules = [];
	private FormAttributeRule $type_rule;
	private array $rules = [];
	/**
	 * @var FormAttributeRule[]
	 */
	private array $modifiers = [];

	public function __construct(string $name)
	{
		$this->name = $name;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function validate(string $name, array $fields, array $body): array
	{
		try {
			if ($this instanceof FormFile) {
				$value = new FormInputValue($_FILES[$name] ?? null, $fields, $body);
				foreach ($this->rules as $rule)
					$rule->process($value);
				return [null, $value->toString()];
			} else {
				$value = new FormInputValue($body[$name] ?? null, $fields, $body);
				foreach ($this->priority_rules as $rule)
					$rule->process($value);
				$this->type_rule->process($value);
				foreach ($this->modifiers as $modifier)
					$modifier->process($value);
				foreach ($this->rules as $rule)
					$rule->process($value);
				return [null, $value->toString()];
			}
		} catch (FormValidationException $e) {
			return [$e, null];
		}
	}

	abstract function field(string $name, string $value): string;

	protected function getValue(mixed $value): mixed
	{
		$val = !$this->forget ? $value : null;
		if (!is_null($this->default) && (is_null($val) || $val === '')) $val = $this->default;
		if (is_null($val)) $val = "";
		return $val;
	}

	protected function addPriorityRule(FormAttributeRule $rule): void
	{
		$this->priority_rules[] = $rule;
	}

	protected function addRule(FormAttributeRule $rule): void
	{
		$this->rules[] = $rule;
	}

	protected function setParam(string $name, $value = null): void
	{
		if (is_null($value))
			$this->params[] = $name;
		else
			$this->params[$name] = $value;
	}

	protected function getParams(): string
	{
		$result = '';
		foreach ($this->params as $key => $value)
			$result .= $key === 0 ? " $value" : " $key=\"$value\"";

		return $result;
	}

	protected function addModifier(FormAttributeRule $rule): void
	{
		$this->modifiers[] = $rule;
	}

	protected function setType(FormAttributeRule $rule): void
	{
		$this->type_rule = $rule;
	}
}