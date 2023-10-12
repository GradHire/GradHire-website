<?php

namespace app\src\model\Form\fields;


use app\src\model\Form\rules\RuleIsEmail;

class FormEmail extends FormString
{
	public function __construct(string $name)
	{
		parent::__construct($name);
		$this->addRule(new RuleIsEmail());
		$this->lowercase();
		$this->trim();
	}


	protected function getType(): string
	{
		return "email";
	}

}