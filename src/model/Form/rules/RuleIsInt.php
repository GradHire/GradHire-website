<?php

namespace app\src\model\Form\rules;


use app\src\model\Form\FormInputValue;

class RuleIsInt extends FormAttributeRule
{

    /**
     * @inheritDoc
     */
    public function process(FormInputValue $value): void
    {
        if (is_string($value->getValue()) && is_numeric($value->getValue()))
            $value->setValue((int)$value->getValue());
    }
}