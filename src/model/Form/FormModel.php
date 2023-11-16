<?php

namespace app\src\model\Form;

use app\src\model\Form\fields\FormAttribute;
use app\src\model\Form\fields\FormCheckbox;
use app\src\model\Form\fields\FormDate;
use app\src\model\Form\fields\FormDouble;
use app\src\model\Form\fields\FormEmail;
use app\src\model\Form\fields\FormFile;
use app\src\model\Form\fields\FormInt;
use app\src\model\Form\fields\FormPassword;
use app\src\model\Form\fields\FormPhone;
use app\src\model\Form\fields\FormRadio;
use app\src\model\Form\fields\FormRangeSlider;
use app\src\model\Form\fields\FormSelect;
use app\src\model\Form\fields\FormString;
use app\src\model\Form\fields\FormSwitch;

class FormModel
{
	private array $fields;
	private array $errors = [];
	private array $js = [];
	private array $body = [];
	/**
	 * @var FileUpload[]
	 */
	private array $files = [];
	private array $parsedBody = [];
	private string $success = '', $error = '';
	private string $action = '', $method = 'post';
	private bool $useFile = false, $memorize;

	public function __construct(array $fields, bool $memorize = true)
	{
		$this->fields = $fields;
		$this->memorize = $memorize;
	}

	public static function string(string $name): FormString
	{
		return new FormString($name);
	}

	public static function switch(string $name): FormSwitch
	{
		return new FormSwitch($name);
	}

	public static function checkbox(string $name, array $values): FormCheckbox
	{
		return new FormCheckbox($name, $values);
	}

	public static function password(string $name): FormPassword
	{
		return new FormPassword($name);
	}

	public static function int(string $name): FormInt
	{
		return new FormInt($name);
	}

	public static function double(string $name): FormDouble
	{
		return new FormDouble($name);
	}

	public static function date(string $name): FormDate
	{
		return new FormDate($name);
	}

	public static function select(string $name, array $options): FormSelect
	{
		return new FormSelect($name, $options);
	}

	public static function radio(string $name, array $options): FormRadio
	{
		return new FormRadio($name, $options);
	}

	public static function email(string $name): FormEmail
	{
		return new FormEmail($name);
	}

	public static function phone(string $name): FormPhone
	{
		return new FormPhone($name);
	}

	public static function file(string $name): FormFile
	{
		return new FormFile($name);
	}

	public static function range(string $name, float $min, float $max): FormRangeSlider
	{
		return new FormRangeSlider($name, $min, $max);
	}

	public function setAction(string $action): void
	{
		$this->action = $action;
	}

	public function setMethod(string $method): void
	{
		$this->method = $method;
	}

	public function print_all_fields(): void
	{
		foreach ($this->fields as $name => $field)
			$this->field($name);
	}

	public function field(string $name): void
	{
		$field = $this->fields[$name] ?? null;
		$value = '';
		if (is_null($field))
			echo "Le champs '" . $name . "' n'existe pas";

		if ($field instanceof FormAttribute) {
			if ($this->memorize && count($this->body) > 0 && isset($this->body[$name]) && $field->checkValue($this->body[$name]))
				$value = $this->body[$name];
			$script = $field->getJS();
			if ($script != "" && (count($this->js) === 0 || !in_array($script, $this->js)))
				$this->js[] = $script;
			$error = $this->errors[$name] ?? null;
			$err = $error && $error !== "" ? '<p class="text-red-600/[.9]"><i class="fa-solid fa-circle-exclamation text-sm mr-2"></i>' . $error . '</p>' : '';
			$label = $field->labelSM ? "text-xs text-zinc-500 font-bold" : "text-sm text-zinc-800 font-bold";
			$border = $field->labelBorder ? "pb-3 border-b-2 border-b-zinc-100" : "";
			echo <<<HTML
<div class="w-full $border">
	<label class="$label">{$field->getName()}</label>
	<div class="mt-2">
		{$field->field($name, $value)}
	</div>
	$err
</div>
HTML;
		}
	}

	public function print_fields(array $fields): void
	{
		foreach ($fields as $name)
			$this->field($name);
	}

	public function validate(array $body): bool
	{
		$this->body = $body;
		foreach ($this->fields as $name => $field)
			if ($field instanceof FormAttribute) {
				[$err, $value] = $field->validate($name, $this->fields, $this->body);
				if (!is_null($err)) {
					$this->errors[$name] = $err;
					$this->parsedBody = [];
					print_r($err);
					return false;
				}
				if ($field instanceof FormFile) {
					if (!is_null($value))
						$this->files[$name] = new FileUpload($value);
				} else {
					$this->parsedBody[$name] = $value;
				}

			}
		return true;
	}

	public function getParsedBody(): array
	{
		return $this->parsedBody;
	}

	public function getFile(string $name): FileUpload|null
	{
		return $this->files[$name] ?? null;
	}

	public function submit(string $text = "Submit", int $order = 1, string $value = ""): void
	{
		$v = $value !== "" ? 'value="' . $value . '"' : '';
		$color = $order === 1 ? "bg-blue-700 hover:bg-blue-800 focus:ring-blue-300" : "bg-zinc-700 hover:bg-zinc-800 focus:ring-zinc-300";
		if ($order === 3) $color = "bg-red-700 hover:bg-red-800 focus:ring-red-300";
		echo <<<HTML
<button type="submit" name="action" $v class="h-[44px] w-full px-5 text-white $color focus:ring-4  font-medium rounded-lg text-sm text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 inline-flex items-center justify-center">
    <svg aria-hidden="true" class="hidden w-4 h-4 mr-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
    </svg>
	$text
</button>
HTML;
	}

	public function reset(string $text = "Reset", bool $removeGet = false): void
	{
		$click = $removeGet ? "window.location.href = window.location.origin + window.location.pathname;" : "window.location = window.location.href";
		echo '<button type="button" onclick="' . $click . '" class="text-white bg-zinc-700 hover:bg-zinc-800 focus:ring-4 focus:outline-none focus:ring-zinc-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-zinc-600 dark:hover:bg-zinc-700 dark:focus:ring-zinc-800">
            ' . $text . '
        </button>';
	}

	public function linkBtn(string $text, string $url): void
	{
		echo <<<HTML
<a class="w-full focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center border-2 border-zinc-800 text-zinc-800 focus:ring-0" href="$url">$text</a>
HTML;
	}

	public function useFile(): void
	{
		$this->useFile = true;
	}

	public function start(): void
	{
		$enctype = $this->useFile ? 'enctype="multipart/form-data"' : '';
		$action = $this->action === '' ? '' : "action='" . $this->action . "'";
		$method = $this->method === '' ? '' : "method='" . $this->method . "'";
		echo <<<HTML
<form $action $method $enctype onsubmit="document.querySelector('button[type=submit] svg').style.display = 'inline';">
HTML;
	}

	public function end(): void
	{
		foreach ($this->js as $script)
			echo '<script src="/resources/js/' . $script . '"></script>';
		echo '</form>';
	}

	public function setError(string $text): void
	{
		$this->error = $text;
	}

	public function setFieldError(string $field, string $error): void
	{
		$this->errors[$field] = $error;
	}

	public function getSuccess(): void
	{
		if (strlen($this->success) > 0)
			echo <<<HTML
<div class="w-full items-center my-1 p-4 bg-green-600/[.2] text-green-600/[.9] rounded-xl flex">
<i class="fa-solid fa-circle-check text-xl mr-3"></i>
<p>{$this->success}</p>
</div>
HTML;
	}

	public function setSuccess(string $text): void
	{
		$this->success = $text;
	}

	public function getError(): void
	{
		if (strlen($this->error) > 0)
			echo <<<HTML
<div class="w-full items-center my-1 p-4 bg-red-600/[.2] text-red-600/[.9] rounded-xl flex">
<i class="fa-solid fa-circle-exclamation text-xl mr-3"></i>
<p>{$this->error}</p>
</div>
HTML;
	}
}