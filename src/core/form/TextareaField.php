<?php

namespace app\src\core\form;

class TextareaField extends BaseField
{
    public function renderInput()
    {
        return sprintf('<textarea class="%s" name="%s">%s</textarea>',
            $this->model->hasError($this->attribute) ? 'border-red-500' : '',
            $this->attribute,
            $this->model->{$this->attribute},
        );
    }
}