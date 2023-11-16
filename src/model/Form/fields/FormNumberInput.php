<?php

namespace app\src\model\Form\fields;


use app\src\model\Form\rules\RuleMaxNumber;
use app\src\model\Form\rules\RuleMinNumber;

class FormNumberInput extends FormInputAttribute
{
    public function min(float $value): static
    {
        $this->addRule(new RuleMinNumber(['min' => $value]));
        $this->setParam("min", $value);
        return $this;
    }

    public function max(float $value): static
    {
        $this->addRule(new RuleMaxNumber(['max' => $value]));
        $this->setParam("max", $value);
        return $this;
    }

    protected function getType(): string
    {
        return "number";
    }
}