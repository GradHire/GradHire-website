<?php

namespace app\src\model\Form\fields;


class FormPassword extends FormString
{
	public function __construct(string $name)
	{
		parent::__construct($name);
		$this->forget();
	}

	function field(string $name, string $value): string
	{
		$input = parent::field($name, $value);
		return <<<HTML
<div class="relative">
  $input
   <i onclick="togglePasswordVisibility(this)" class="fa-solid fa-eye text-zinc-500 cursor-pointer absolute right-4 top-1/2 transform -translate-y-1/2"></i>
</div>
HTML;
	}

	public function getJS(): string
	{
		return 'password.js';
	}

	protected function getType(): string
	{
		return 'password';
	}
}