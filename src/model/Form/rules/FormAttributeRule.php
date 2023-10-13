<?php

namespace app\src\model\Form\rules;

use app\src\model\Form\FormInputValue;
use app\src\model\Form\FormValidationException;

abstract class FormAttributeRule
{
	private array $options;

	public function __construct(array $options = array())
	{
		$this->options = $options;
	}

	public function getOption($name, mixed $default = null): mixed
	{
		return !isset($this->options[$name]) ? $default : $this->options[$name];
	}

	/**
	 * @throws FormValidationException
	 */
	abstract public function process(FormInputValue $value): void;
}