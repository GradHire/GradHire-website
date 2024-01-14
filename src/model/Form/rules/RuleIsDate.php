<?php

namespace app\src\model\Form\rules;


use app\src\model\Form\FormInputValue;
use app\src\model\Form\FormValidationException;
use DateTime;

class RuleIsDate extends FormAttributeRule
{

    /**
     * @inheritDoc
     */
    public function process(FormInputValue $value): void
    {
        if ($value->getValue() === "") {
            $value->setValue(null);
            return;
        }
        if ($value->getValue() instanceof DateTime)
            return;

        if (is_string($value->getValue()) && false !== strtotime($value->getValue()))
            return;

        throw new FormValidationException("Ce champs n'est pas une date.");
    }
}