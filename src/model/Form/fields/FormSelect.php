<?php

namespace app\src\model\Form\fields;


use app\src\model\Form\rules\RuleInArray;
use app\src\model\Form\rules\RuleIsString;

class FormSelect extends AnyAttribute
{
    private array $options;

    public function __construct(string $name, array $options)
    {
        parent::__construct($name);
        $this->options = $options;
        $this->setType(new RuleIsString());
        $this->addRule(new RuleInArray(["allowed" => array_keys($options)]));
    }


    function field(string $name, string $value): string
    {
        $value = $this->getValue($value);
        $select = '<select name="' . $name . '" ' . $this->getParams() . ' class=" w-full text-zinc-700 rounded-lg sm:text-sm p-2 block cursor-pointer border border-zinc-300 bg-zinc-50 text-sm font-medium hover:border-zinc-200 peer-checked:border-zinc-500 peer-checked:ring-1 peer-checked:ring-zinc-500">';
        $select .= '<option value="" disabled>Select an option</option>';
        foreach ($this->options as $fValue => $label) {
            $select .= "<option value=\"$fValue\" " . (strval($value) === strval($fValue) ? 'selected="selected"' : '') . " >$label</option>";
        }
        $select .= "</select>";
        return $select;
    }
}