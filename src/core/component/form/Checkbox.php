<?php

namespace app\src\core\form;

use app\src\model\Model;

class Checkbox extends BaseField
{
	public function __construct(Model $model, string $attribute)
	{
		parent::__construct($model, $attribute);
	}

	public function renderInput()
	{
		return sprintf('<input type="checkbox" value="true" name="%s" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-gray-500 dark:focus:border-gray-500 dark:shadow-sm-light %s">',
			$this->attribute,
			$this->model->{$this->attribute},
		);
	}
}