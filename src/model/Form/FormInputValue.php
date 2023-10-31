<?php

namespace app\src\model\Form;


use app\src\model\Form\fields\FormAttribute;
use DateTime;

class FormInputValue
{
    private mixed $value;
    private array $fields;
    private array $body;

    public function __construct(mixed $value, array $fields, array $body)
    {
        $this->value = $value;
        $this->fields = $fields;
        $this->body = $body;
    }

    public function getBodyValue(string $name): mixed
    {
        return $this->body[$name] ?? null;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setValue($value): void
    {
        $this->value = $value;
    }

    public function toString(): mixed
    {
        if ($this->value instanceof DateTime)
            return $this->value->format('Y-m-d');
        return $this->value;
    }

    public function getDate(): DateTime|null
    {
        if (is_null($this->value))
            return null;
        if ($this->value instanceof DateTime)
            return $this->value;

        try {
            $this->setValue(new DateTime($this->value));
            return $this->value;
        } catch (\Exception $e) {
            die();
        }
    }

    public function getField(string $name): FormAttribute|null
    {
        return $this->fields[$name] ?? null;
    }
}