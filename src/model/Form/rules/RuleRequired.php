<?php

namespace app\src\model\Form\rules;


use app\src\model\Form\FormInputValue;
use app\src\model\Form\FormValidationException;

class RuleRequired extends FormAttributeRule
{
    public function process(FormInputValue $value): void
    {
        if ($value->getValue() === null)
            throw new FormValidationException('Ce champs est requis');
        if (is_string($value->getValue()) && !strlen($value->getValue()))
            throw new FormValidationException('Ce champs est requis');
    }
}