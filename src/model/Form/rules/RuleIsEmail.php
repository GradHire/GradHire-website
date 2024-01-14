<?php

namespace app\src\model\Form\rules;


use app\src\model\Form\FormInputValue;
use app\src\model\Form\FormValidationException;

class RuleIsEmail extends FormAttributeRule
{

    /**
     * @inheritDoc
     */
    public function process(FormInputValue $value): void
    {
        if ($value->getValue() === "") return;
        if (!filter_var($value->getValue(), FILTER_VALIDATE_EMAIL))
            throw new FormValidationException("Veuillez saisir une adresse mail valide");
    }
}