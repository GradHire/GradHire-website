<?php

namespace app\src\model\Form\fields;

use app\src\model\Form\rules\RuleFileExtension;
use app\src\model\Form\rules\RuleFileMaxSize;

class FormFile extends FormAttribute
{
	function field(string $name, string $value): string
	{
		return '<input type="file" name="' . $name . '" ' . $this->getParams() . ' class="block w-full border border-zinc-200 shadow-sm rounded-md text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-zinc-700 dark:text-zinc-400
		    file:bg-transparent file:border-0
		    file:bg-zinc-200 file:mr-4
		    file:py-3 file:px-4
		    dark:file:bg-zinc-700 dark:file:text-zinc-400"/>';
	}

	public function image(): static
	{
		$this->accept([".png", ".jpg", ".jpeg"]);
		return $this;
	}

	/**
	 * @param string[] $extensions
	 * @return static
	 */
	public function accept(array $extensions): static
	{
		$this->addRule(new RuleFileExtension(["extensions" => $extensions]));
		$this->setParam("accept", implode(",", $extensions));
		return $this;
	}

	public function pdf(): static
	{
		$this->accept([".pdf"]);
		return $this;
	}

	public function maxSize(int $maxSize): static
	{

		$this->addRule(new RuleFileMaxSize(["maxSize" => $maxSize]));
		return $this;
	}
}