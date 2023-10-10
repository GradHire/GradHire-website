<?php

namespace app\src\model\Form;

class FormString extends FormModelAttribute
{
	private string $field_type = "text";
	private bool $numeric = false;

	public function validate($value, array $data): string|null
	{
		$result = parent::validate($value, $data);
		if (!is_null($result)) return $result;
		if ($this->min >= 0 && strlen($value) < $this->min) return "Le champs doit faire au moins $this->min charactères";
		if ($this->max >= 0 && strlen($value) > $this->max) return "Le champs doit faire au maximum $this->max charactères";
		if ($this->field_type == "email" && !filter_var($value, FILTER_VALIDATE_EMAIL)) return "Adresse mail invalide";
		if ($this->numeric && !ctype_digit($value)) return "Le champs ne doit contenir uniquement des chiffres";
		return null;
	}

	public function numeric(): FormString
	{
		$this->numeric = true;
		return $this;
	}

	public function date(): FormString
	{
		$this->field_type = "date";
		return $this;
	}

	public function password(): FormString
	{
		$this->field_type = "password";
		return $this;
	}

	public function email(): FormString
	{
		$this->field_type = "email";
		return $this;
	}

	function field(string $name): string
	{
		return '<input type="' . $this->field_type . '" name="' . $name . '" value="' . (is_null($this->default) ? "" : $this->default) . '" class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light %s">';
	}

	function get_type(): string
	{
		return "string";
	}
}