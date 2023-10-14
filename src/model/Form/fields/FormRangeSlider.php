<?php

namespace app\src\model\Form\fields;

use app\src\model\Form\rules\RuleIsRange;

class FormRangeSlider extends AnyAttribute
{
	protected string $step = "0.01";
	protected string $min, $max;

	public function __construct(string $name, float $min, float $max)
	{
		parent::__construct($name);

		$this->default = [$min, $max];
		$this->setType(new RuleIsRange(["useInt" => $this->step === "1"]));
		$this->min = $min;
		$this->max = $max;
	}

	public function default($value): static
	{
		if (!is_array($value) || count($value) != 2) {
			echo "La valeur par défaut d'un range input doit être une array de 2 éléments correspondant à min et max.";
			die();
		}
		if ($value[0] > $value[1]) {
			echo "Min doit inférieur ou égale à max.";
			die();
		}
		if ($value[0] < $this->min || $value[0] > $this->max || $value[1] < $this->min || $value[1] > $this->max) {
			echo "Les valeurs par défauts doivent être comprise entre $this->min et $this->max.";
			die();
		}
		return parent::default($value);
	}


	function field(string $name, $value): string
	{
		$value = $this->getValue($value);
		$required = in_array("required", $this->params) ? 'required' : '';
		return <<<HTML
	<div class="range-slider">
		<div class="range-spans flex w-full flex-row justify-between items-center text-xs text-zinc-500">
		    <span>0</span>
		    <span>100</span>
		</div>
		<div class="range-sliders relative w-full h-[25px]">
		    <div class="slider-track"></div>
		    <input type="range" min="{$this->min}" max="{$this->max}" value="{$value[0]}" step="{$this->step}" name="{$name}[]" $required>
		    <input type="range" min="{$this->min}" max="{$this->max}" value="{$value[1]}" step="{$this->step}" name="{$name}[]" $required>
		</div>
	</div>
HTML;
	}

	public function int(): static
	{
		$this->step = "1";
		return $this;
	}

	public function getJS(): string
	{
		return 'rangeSlider.js';
	}

	private function parseRange(string $value, &$prev): bool
	{
		$values = explode(',', $value);
		if (count($values) != 2)
			return false;
		$value1 = filter_var($values[0], FILTER_VALIDATE_FLOAT);
		$value2 = filter_var($values[1], FILTER_VALIDATE_FLOAT);

		if ($value1 === false || $value2 === false || $value2 < $value1) return false;
		$prev = [$value1, $value2];
		return true;
	}
}