<?php

namespace app\src\model\Form\fields;

abstract class FormInputAttribute extends AnyAttribute
{
    protected bool $textArea = false;

    function field(string $name, string $value): string
    {
        if ($this->textArea)
            return '<textarea name="' . $name . '" ' . $this->getParams() . ' class="shadow-sm w-full bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light">' . $this->getValue($value) . '</textarea>';
        return '<input type="' . $this->getType() . '" name="' . $name . '"  ' . $this->getParams() . ' value="' . $this->getValue($value) . '" class="shadow-sm w-full bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light">';
    }

    abstract protected function getType(): string;
}