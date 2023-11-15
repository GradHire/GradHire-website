<?php

namespace app\src\model\Form\fields;


use app\src\model\Form\FormInputValue;
use app\src\model\Form\FormValidationException;
use app\src\model\Form\rules\FormAttributeRule;
use app\src\model\Form\rules\RuleRequired;

abstract class FormAttribute
{
    public bool $labelSM = false;
    public bool $labelBorder = false;
    protected string $name;
    protected bool $forget = false;
    protected bool $asterisk = false;
    protected mixed $default;
    protected array $params = [];
    /**
     * @var FormAttributeRule[]
     */
    private array $priority_rules = [];
    private FormAttributeRule $type_rule;
    private array $rules = [];
    /**
     * @var FormAttributeRule[]
     */
    private array $modifiers = [];

    public function __construct(string $name = "")
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name . ($this->asterisk ? '<span class="text-red-500"> *</span>' : '');
    }


    public function validate(string $name, array $fields, array $body): array
    {
        try {
            if ($this instanceof FormFile) {
                $file = $_FILES[$name] ?? null;
                if (!is_null($file) && (is_null($file["name"]) || $file["name"] === "")) $file = null;
                $value = new FormInputValue($file, $fields, $body);
            } else {
                $val = $body[$name] ?? null;
                if (is_null($val) && $this->default != null)
                    $val = $this->default;
                if ((is_null($val) || $val === "") && $this->isNullable)
                    return [null, null];
                $value = new FormInputValue($val, $fields, $body);
                foreach ($this->priority_rules as $rule)
                    $rule->process($value);
                $this->type_rule->process($value);
                foreach ($this->modifiers as $modifier)
                    $modifier->process($value);
            }
            foreach ($this->rules as $rule)
                $rule->process($value);
            return [null, $value->toString()];
        } catch (FormValidationException $e) {
            return [$e, null];
        }
    }

    public function required(): static
    {
        $this->addPriorityRule(new RuleRequired());
        $this->setParam("required");
        return $this;
    }

    protected function addPriorityRule(FormAttributeRule $rule): void
    {
        $this->priority_rules[] = $rule;
    }

    protected function setParam(string $name, $value = null): void
    {
        if (is_null($value))
            $this->params[] = $name;
        else
            $this->params[$name] = $value;
    }

    public function sm(): static
    {
        $this->labelSM = true;
        return $this;
    }

    public function border(): static
    {
        $this->labelBorder = true;
        return $this;
    }

    public function id(string $id): static
    {
        $this->setParam("id", $id);
        return $this;
    }

    public function params(array $attributes): static
    {
        foreach ($attributes as $key => $value)
            if (is_null($value))
                $this->setParam($key);
            else
                $this->setParam($key, $value);
        return $this;
    }

    abstract function field(string $name, string $value): string;

    public function getJS(): string
    {
        return '';
    }

    public function checkValue($value): bool
    {
        try {
            $this->type_rule->process(new FormInputValue($value, [], []));
            return true;
        } catch (FormValidationException $e) {
            return false;
        }
    }

    protected function getValue(mixed $value): mixed
    {
        $val = !$this->forget ? $value : null;
        if (!is_null($this->default) && (is_null($val) || $val === '')) $val = $this->default;
        if (is_null($val)) $val = "";
        return $val;
    }

    protected function addRule(FormAttributeRule $rule): void
    {
        $this->rules[] = $rule;
    }

    protected function getParams(): string
    {
        $result = '';
        foreach ($this->params as $key => $value)
            $result .= $key === 0 ? " $value" : " $key=\"$value\"";

        return $result;
    }

    protected function addModifier(FormAttributeRule $rule): void
    {
        $this->modifiers[] = $rule;
    }

    protected function setType(FormAttributeRule $rule): void
    {
        $this->type_rule = $rule;
    }
}