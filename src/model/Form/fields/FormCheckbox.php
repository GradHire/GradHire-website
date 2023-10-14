<?php

namespace app\src\model\Form\fields;

use app\src\model\Form\rules\RuleIsArray;

class FormCheckbox extends AnyAttribute
{
	private array $values;
	private bool $horizontal = false;

	/**
	 * @param string $name
	 * @param array $values
	 */
	public function __construct(string $name, array $values)
	{
		parent::__construct($name);
		$this->values = $values;
		$this->default = [];
		$this->setType(new RuleIsArray());
	}


	/**
	 * @param string[] $value
	 * @return $this
	 */
	public function default($value): static
	{
		$this->default = $value;
		return $this;
	}

	function field(string $name, mixed $value): string
	{
		$value = $this->getValue($value);
		$res = '<div class="flex gap-2 ' . ($this->horizontal ? 'flex-row' : 'flex-col') . '">';
		foreach ($this->values as $id => $val) {
			$checked = (in_array($id, $value) ? "checked" : '');
			$res .= <<<HTML
<div class="w-full">
	<input
	        type="checkbox"
	        name="{$name}[]"
	        value="$id"
	        id="$id"
	        $checked
	        class="peer hidden [&:checked_+_label_svg]:block"
	/>
	
	<label
	        for="$id"
	        class="block cursor-pointer rounded-lg border border-zinc-100 bg-white p-2 text-sm font-medium hover:border-zinc-200 peer-checked:border-zinc-500 peer-checked:ring-1 peer-checked:ring-zinc-500"
	>
	<span class="flex items-center justify-between">
	    <span class="text-zinc-700">$val</span>
	
	    <svg
	            class="hidden h-5 w-5 text-zinc-600"
	            xmlns="http://www.w3.org/2000/svg"
	            viewBox="0 0 20 20"
	            fill="currentColor"
	    >
	        <path
	                fill-rule="evenodd"
	                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
	                clip-rule="evenodd"
	        />
	    </svg>
	</span>
	</label>
</div>
HTML;
		}
		$res .= '</div>';
		return $res;
	}

	public function horizontal(): static
	{
		$this->horizontal = true;
		return $this;
	}

	protected function getType(): string
	{
		return "checkbox";
	}
}