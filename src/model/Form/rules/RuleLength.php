<?php

namespace app\src\model\Form\rules;

use app\src\model\Form\FormInputValue;
use app\src\model\Form\FormValidationException;

class RuleLength extends FormAttributeRule
{

    /**
     * @inheritDoc
     */
    public function process(FormInputValue $value): void
    {
        $number = $this->getOption('length');
        $length = strlen($value->getValue());
        if ($length !== $number)
            throw new FormValidationException(sprintf('Ce champs doit faire %d charactères', $number));
    }
}