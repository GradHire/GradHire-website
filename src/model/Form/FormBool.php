<?php

namespace app\src\model\Form;

class FormBool extends FormModelAttribute
{
	public function __construct(string $label)
	{
		parent::__construct($label);
		$this->values(["true", "false"]);
		$this->nullable(true);
		$this->default(false);
	}

	function get_type(): string
	{
		return "string";
	}

	function field(string $name): string
	{
		return '<input type="checkbox" value="true" name="' . $name . '" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-gray-500 dark:focus:border-gray-500 dark:shadow-sm-light">';
	}

	function parse_value($value): bool
	{
		return $value === "true";
	}
}