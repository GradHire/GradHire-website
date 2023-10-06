<?php

namespace app\src\core\component\form;


use app\src\model\Model;

class Field extends BaseField
{
    const TYPE_TEXT = 'text';
    const TYPE_PASSWORD = 'password';
    const TYPE_FILE = 'file';
    const TYPE_DATE = 'date';
    public function __construct(Model $model, string $attribute)
    {
        $this->type = self::TYPE_TEXT;
        parent::__construct($model, $attribute);
    }

    public function renderInput()
    {
        return sprintf('<input type="%s" name="%s" value="%s" class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light %s">',
            $this->type,
            $this->attribute,
            $this->model->{$this->attribute},
            $this->model->hasError($this->attribute) ? 'border-red-500' : '',
        );
    }

	public function passwordField()
	{
		$this->type = self::TYPE_PASSWORD;
		return $this;
	}

    public function fileField()
    {
        $this->type = self::TYPE_FILE;
        return $this;
    }
    public function dateField()
    {
        $this->type = self::TYPE_DATE;
        return $this;
    }
}