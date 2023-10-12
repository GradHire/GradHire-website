<?php

namespace app\src\model\Form\fields;


use app\src\model\Form\rules\RuleDateAfter;
use app\src\model\Form\rules\RuleDateBefore;
use app\src\model\Form\rules\RuleDateBetween;
use app\src\model\Form\rules\RuleIsDate;
use DateTime;

class FormDate extends FormInputAttribute
{
	public function __construct(string $name)
	{
		parent::__construct($name);
		setlocale(LC_TIME, 'fr_FR.utf8');
		$this->setType(new RuleIsDate());
	}

	public function after(DateTime $date): static
	{
		$this->addRule(new RuleDateAfter(["after" => $date]));
		$this->setParam("min", $date->format('Y-m-d'));
		return $this;
	}

	public function before(DateTime $date): static
	{
		$this->addRule(new RuleDateBefore(["before" => $date]));
		$this->setParam("max", $date->format('Y-m-d'));
		return $this;
	}

	public function between(DateTime $before, DateTime $after): static
	{
		$this->addRule(new RuleDateBetween(["before" => $before, "after" => $after]));
		$this->setParam("min", $before->format('Y-m-d'));
		$this->setParam("max", $after->format('Y-m-d'));
		return $this;
	}

	protected function getType(): string
	{
		return "date";
	}
}