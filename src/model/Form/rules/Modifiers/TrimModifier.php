<?php

namespace app\src\model\Form\rules\Modifiers;


use app\src\model\Form\FormInputValue;
use app\src\model\Form\rules\FormAttributeRule;

class TrimModifier extends FormAttributeRule
{

    /**
     * @inheritDoc
     */
    public function process(FormInputValue $value): void
    {
        $value->setValue(trim($value->getValue()));
    }
}