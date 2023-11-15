<?php

namespace app\src\core\components;

class Table
{
	public static function createTable(array $values, array $columns, callable $callback)
	{
		$sort = $_GET['sort'] ?? null;
		$order = $_GET['order'] ?? null;

		echo <<<HTML
<table class="min-w-full divide-y-2 divide-zinc-200 bg-white text-sm" id="table">
        <thead class="ltr:text-left rtl:text-right">
        <tr>
HTML;
		for ($i = 0; $i < count($columns); $i++) {
			echo '<th class="select-none cursor-pointer whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900 hover:bg-gray-100" onclick="sortTable(this, ' . $i . ')">
				<div class="flex justify-between items-center">
				' . $columns[$i] . ' <i class="fa-solid fa-sort"></i>
				</div>                
            </th>';
		}


		echo <<<HTML
        </tr>
        </thead>

        <tbody class="divide-y divide-zinc-200">
HTML;
		if ($values == null || count($values) == 0) {
			echo <<<HTML
<tr class="odd:bg-zinc-50">
<td class="whitespace-nowrap px-4 py-2 font-medium text-zinc-900" colspan="5">
Aucun résultat
</td>
</tr>
HTML;
		} else {
			foreach ($values as $value) {
				echo <<<HTML
<tr class="odd:bg-zinc-50">
HTML;
				$callback($value);
				echo <<<HTML
</tr>
HTML;
			}
		}
		echo <<<HTML
</tbody>
    </table>
<script type="text/javascript" src="/resources/js/table.js"></script>
HTML;
	}

	public static function button($link, $text = "Voir plus")
	{
		self::cell(<<<HTML
	<a href="$link"
	   class="inline-block rounded bg-zinc-600 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700">$text</a>
HTML
		);
	}

	public static function cell($value)
	{
		echo <<<HTML
<td class="whitespace-nowrap px-4 py-2 font-medium text-zinc-900">
$value
</td>
HTML;
	}

	public static function chip($value, $color)
	{
		$c = "bg-green-100 text-green-800";
		if ($color == "red")
			$c = "bg-red-100 text-red-800";
		elseif ($color == "yellow")
			$c = "bg-yellow-100 text-yellow-800";
		self::cell(<<<HTML
<span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 $c">$value</span>
HTML
		);
	}

	public static function link($link)
	{
		self::cell(($link == null || $link == "") ? "" : <<<HTML
<a target="_blank" href="$link" class="text-blue-600">$link</a>
HTML
		);
	}

	public static function mail($mail)
	{
		self::cell(($mail == null || $mail == "") ? "" : <<<HTML
<a href="mailto:$mail">$mail</a>
HTML
		);
	}

	public static function phone($phone)
	{
		self::cell(($phone == null || $phone == "") ? "" : <<<HTML
<a href="tel:$phone">$phone</a>
HTML
		);
	}

}