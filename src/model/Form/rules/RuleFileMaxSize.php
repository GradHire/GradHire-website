<?php

namespace app\src\model\Form\rules;

use app\src\model\Form\FormInputValue;
use app\src\model\Form\FormValidationException;

class RuleFileMaxSize extends FormAttributeRule
{

    /**
     * @inheritDoc
     */
    public function process(FormInputValue $value): void
    {
        if (!is_null($value->getValue()) && $value->getValue()["size"] > $this->getOption("maxSize"))
            throw new FormValidationException("Le fichier doit faire au maximum " . $this->getOption("maxSize") . " octets");
    }
}