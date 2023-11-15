<?php

namespace app\src\model\Form\rules;


use app\src\model\Form\FormInputValue;
use app\src\model\Form\FormValidationException;

class RuleIsValidArray extends FormAttributeRule
{

    /**
     * @inheritDoc
     */
    public function process(FormInputValue $value): void
    {
        $arr = $value->getValue();
        foreach ($arr as $item) {
            if (!in_array($item, $this->getOption('allowed')))
                throw new FormValidationException(sprintf("La valeur '%s' n'est pas une valeur valide", $item));
        }
    }
}