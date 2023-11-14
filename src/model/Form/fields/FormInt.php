<?php

namespace app\src\model\Form\fields;


use app\src\model\Form\rules\RuleIsInt;

class FormInt extends FormNumberInput
{
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->setType(new RuleIsInt());
        $this->setParam("step", "1");
    }
}