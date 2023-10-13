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

    public static function checkbox(string $name): FormCheckbox
    {
        return new FormCheckbox($name);
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
        if ($this->memorize && count($this->body) > 0 && isset($this->body[$name]))
            $value = $this->body[$name];
        if ($field instanceof FormAttribute) {
            $script = $field->getJS();
            if ($script != "" && (count($this->js) === 0 || !in_array($script, $this->js)))
                $this->js[] = $script;
            echo '<div class="form-group">
                <label>' . $field->getName() . '</label>                
			<div class="invalid-feedback">
                    ' . $field->field($name, $value) . '
			</div>
			' . ($this->errors[$name] ?? null) . '
            </div>';
        }
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

    public function submit(string $text = "Submit"): void
    {
        echo '<button class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
            ' . $text . '
        </button>';
    }

    public function useFile(): void
    {
        $this->useFile = true;
    }

    public function start(): void
    {
        echo '<form action="' . $this->action . '" method="' . $this->method . '" ' . ($this->useFile ? 'enctype="multipart/form-data"' : '') . '>';
    }

    public function end(): void
    {
        foreach ($this->js as $script)
            echo '<script type="text/javascript" src="/resources/js/' . $script . '"></script>';
        echo '</form>';
    }

    public function setError(string $text): void
    {
        $this->error = $text;
    }

    public function getSuccess(): void
    {
        if (strlen($this->success) > 0)
            echo '<p>' . $this->success . '</p>';
    }

    public function setSuccess(string $text): void
    {
        $this->success = $text;
    }

    public function getError(): void
    {
        if (strlen($this->error) > 0)
            echo '<p>' . $this->error . '</p>';
    }
}