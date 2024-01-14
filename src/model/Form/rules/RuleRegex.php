<?php

namespace app\src\model\Form\rules;


use app\src\model\Form\FormInputValue;
use app\src\model\Form\FormValidationException;

class RuleRegex extends FormAttributeRule
{

    /**
     * @inheritDoc
     */
    public function process(FormInputValue $value): void
    {
        $pattern = $this->getOption('pattern');
        $message = $this->getOption('message', sprintf('La valeur ne correspond pas au pattern %s', $pattern));

        if (!preg_match($this->getOption('pattern'), $value->getValue()))
            throw new FormValidationException($message);
    }
}