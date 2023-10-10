<?php

namespace app\src\model\Form;

class FormModel
{
	protected string $error, $success;
	private array $attributes;
	private array $errors;
	private string $action, $method;
	private array $data;

	public function __construct(array $attributes)
	{
		$this->attributes = $attributes;
		$this->action = '';
		$this->method = "post";
		$this->data = [];
		$this->error = '';
		$this->success = '';
	}

	public function print_all_fields()
	{
		foreach ($this->attributes as $key => $value)
			$this->field($key);
	}

	public function field(string $name): void
	{
		if ($this->attributes[$name] && $this->attributes[$name] instanceof FormModelAttribute) {
			echo '<div class="form-group">
                <label>' . $this->attributes[$name]->getLabel() . '</label>                
			<div class="invalid-feedback">
                    ' . $this->attributes[$name]->field($name) . '
			</div>
			' . $this->get_error($name) . '
            </div>';
		}
		echo '';
	}

	private function get_error(string $name): string
	{
		return $this->errors[$name] ?? "";
	}

	public function setAction(string $action): void
	{
		$this->action = $action;
	}

	public function setMethod(string $method): void
	{
		$this->method = $method;
	}

	public function validate($data): bool
	{
		$this->data = $data;
		$error_found = false;
		$this->errors = [];
		try {
			foreach ($this->attributes as $key => $value) {
				if ($value instanceof FormModelAttribute) {
					$error = $value->validate($data[$key] ?? null, $this->data);
					if ($error) {
						$error_found = true;
						$this->errors[$key] = $error;
					}
				}
			}
			return !$error_found;
		} catch (\Exception) {
			return false;
		}
	}

	public function get_data(): array
	{
		$res = [];
		foreach ($this->attributes as $key => $value)
			if ($value instanceof FormModelAttribute)
				$res[$key] = array_key_exists($key, $this->data) ? $value->parse_value($this->data[$key]) : $value->getDefault();
		return $res;
	}

	public function submit(string $text = "Submit"): void
	{
		echo '<button class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
            ' . $text . '
        </button>';
	}

	public function start(): void
	{
		echo '<form action="' . $this->action . '" method="' . $this->method . '">';
	}

	public function end(): void
	{
		echo '</form>';
	}

	public function add_error(string $text)
	{
		$this->error = $text;
	}

	public function add_success(string $text)
	{
		$this->success = $text;
	}

	public function getError(): string
	{
		return $this->error;
	}

	public function getSuccess(): string
	{
		return $this->success;
	}
}