<?php

namespace app\src\model\Form\rules;


use app\src\model\Form\FormInputValue;
use app\src\model\Form\FormValidationException;

class RuleInArray extends FormAttributeRule
{

    /**
     * @inheritDoc
     */
    public function process(FormInputValue $value): void
    {
        if (!in_array($value->getValue(), $this->getOption('allowed')))
            throw new FormValidationException(sprintf("La valeur '%s' n'est pas une valeur valide", $value->getValue()));
    }
}