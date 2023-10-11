<?php

namespace app\src\model\Form;

abstract class FormModelAttribute
{
    protected string $label;
    protected bool $nullable = false;
    protected mixed $default = null;
    protected array $values;
    protected string $match_attribute = '';
    protected bool $required = false, $empty = false;
    protected int $min = -1, $max = -1;

    /**
     * @param string $label
     */
    public function __construct(string $label)
    {
        $this->label = $label;
        $this->values = [];
    }


    public static function New(string $label): static
    {
        return new static($label);
    }

    public function default($value): static
    {
        $this->default = $value;
        return $this;
    }

    public function empty(): static
    {
        $this->empty = true;
        return $this;
    }

    public function nullable(bool $value): static
    {
        $this->nullable = $value;
        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getDefault(): mixed
    {
        return $this->default;
    }

    public function required(): static
    {
        $this->required = true;
        return $this;
    }

    public function min(int $value): static
    {
        $this->min = $value;
        return $this;
    }

    public function max(int $value): static
    {
        $this->max = $value;
        return $this;
    }

    public function validate($value, array $data): string|null
    {
        if ($this->isNull($value) && $this->nullable) return null;
        if ($this->required && $this->isNull($value) && !$this->nullable) return "Veuillez remplir ce champs";
        if (gettype($value) != $this->get_type()) return "Valeur incorrect type '" . $this->get_type() . "' attendu";
        if (count($this->values) > 0 && !in_array($value, $this->values, true)) return "Veuillez saisir une valeur valide";
        if ($this->match_attribute !== '' && (is_null($data[$this->match_attribute] ?? null) || $data[$this->match_attribute] !== $value)) return "Veuillez saisir la même valeur";
        return null;
    }

    protected function isNull($value): bool
    {
        return is_null($value) || $value === "";
    }

    abstract function get_type(): string;

    public function match(string $attribute): static
    {
        $this->match_attribute = $attribute;
        return $this;
    }

    public function values(array $values): static
    {
        $this->values = $values;
        return $this;
    }

    abstract function field(string $name): string;

    function parse_value($value): mixed
    {
        if ($value === "" && $this->empty && $this->get_type() === "date") return null;
        return $value;
    }
}