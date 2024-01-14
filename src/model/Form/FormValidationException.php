<?php

namespace app\src\model\Form;

use Exception;

class FormValidationException extends Exception
{
    public function __toString(): string
    {
        return $this->getMessage();
    }
}