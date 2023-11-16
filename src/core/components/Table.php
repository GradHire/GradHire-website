<?php

namespace app\src\core\components;

class Table
{
    public static function createTable(array $values, array $columns, callable $callback)
    {
        $i = (time() + rand(0, 1000));
        $id = "table-" . $i;
        $cols = count($columns);
        $empty = 'empty-' . $i;

        echo <<<HTML
<div class="flex mb-4 gap-2 pr-2">
    <input onkeydown="if(event.key === 'Enter')search('search-$i', '$id', $cols, '$empty', 'clear-$i')" type="text" id="search-$i" placeholder="Rechercher" class="shadow-sm w-full bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block px-2.5 py-2 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light">
   <button onclick="search('search-$i', '$id', $cols, '$empty', 'clear-$i')" class="text-white bg-zinc-700 hover:bg-zinc-800 focus:ring-4 focus:outline-none focus:ring-zinc-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-zinc-600 dark:hover:bg-zinc-700 dark:focus:ring-zinc-800">Rechercher</button>
<button style="display: none" id="clear-$i" onclick="clearInput('search-$i');search('search-$i', '$id', $cols, '$empty', 'clear-$i')" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">X</button>
</div>
<table class="min-w-full divide-y-2 divide-zinc-200 bg-white text-sm" id="$id">
        <thead class="ltr:text-left rtl:text-right">
        <tr>
HTML;
        for ($i = 0; $i < count($columns); $i++) {
            echo '<th class="select-none cursor-pointer whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900 hover:bg-gray-100" onclick="sortTable(\'' . $id . '\', this, ' . $i . ')">
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
        if (count($values) > 0) {
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
        $style = count($values) > 0 ? "display: none" : '';
        echo <<<HTML
</tbody>
    </table>
    <p id="$empty" style="$style" class="pl-4 pt-2 text-zinc-800">Aucun résultats</p>
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

    public static function pdfLink($link, $text)
    {
        self::cell(($link == null || $link == "") ? "" : <<<HTML
        <a target="_blank" href="$link" class="text-blue-600"> $text </a>
    HTML
        );
    }


}