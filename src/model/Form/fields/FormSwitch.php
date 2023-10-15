<?php

namespace app\src\model\Form\fields;

use app\src\model\Form\rules\RuleIsBoolean;

class FormSwitch extends AnyAttribute
{
	protected string $style = "switch";
	private bool $red = false;

	public function __construct(string $name)
	{
		parent::__construct($name);
		$this->setType(new RuleIsBoolean());
	}


	/**
	 * @param bool $value
	 * @return $this
	 */
	public function default($value): static
	{
		$this->default = $value ? "on" : "";
		return $this;
	}

	function field(string $name, string $value): string
	{
		$value = $this->getValue($value);
		$checked = ($value === "on" ? "checked" : '');
		if ($this->style === "basic")
			return <<<HTML
				<input type="checkbox" name="{$name}" {$checked} {$this->getParams()}/>
			HTML;
		$color = $this->red ? "peer-checked:bg-red-500" : "peer-checked:bg-green-500";
		return <<<HTML
			<div class="flex flex-row gap-2">
				<label for="AcceptConditions" class="relative h-5 w-10 cursor-pointer">
					<input type="checkbox" id="AcceptConditions" name="{$name}" class="peer sr-only" {$checked}/>
					<span class="absolute inset-0 rounded-full  bg-zinc-800 transition border-[1px] border-zinc-100 peer-checked:bg-green-500 [&:not(peer-checked)]:bg-red-500"></span>
					<span class="absolute shadow inset-y-0 start-0 m-1 h-3 w-3 rounded-full bg-white border-[1px] border-zinc-100 transition-all peer-checked:start-5"></span>
				</label>
			</div>
		HTML;
	}

	public function basic(): static
	{
		$this->style = "basic";
		return $this;
	}

	protected function getType(): string
	{
		return "checkbox";
	}
}