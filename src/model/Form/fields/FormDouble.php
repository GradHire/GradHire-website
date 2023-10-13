<?php

namespace app\src\model\Form\fields;


use app\src\model\Form\rules\RuleIsDouble;

class FormDouble extends FormNumberInput
{
	public function __construct(string $name)
	{
		parent::__construct($name);
		$this->setType(new RuleIsDouble());
		$this->setParam("step", "0.01");
	}
}