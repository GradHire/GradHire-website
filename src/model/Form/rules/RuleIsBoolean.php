<?php

namespace app\src\model\Form\rules;


use app\src\model\Form\FormInputValue;
use app\src\model\Form\FormValidationException;

class RuleIsBoolean extends FormAttributeRule
{

    /**
     * @inheritDoc
     */
    public function process(FormInputValue $value): void
    {
        if (!is_null($value->getValue()) && $value->getValue() !== "on")
            throw new FormValidationException("Valeur invalide");
        $value->setValue($value->getValue() === "on");
    }
}