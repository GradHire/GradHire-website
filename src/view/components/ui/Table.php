<?php

namespace app\src\view\components\ui;

use app\src\view\resources\icons\I_Chevron;
use app\src\view\resources\icons\I_Close;
use app\src\view\resources\icons\I_Search;

class Table
{
    public static function createTable(array $values, array $columns, callable $callback): void
    {
        $i = (time() + rand(0, 1000));
        $id = "table-" . $i;
        $cols = count($columns);
        $empty = 'empty-' . $i;

        $buttons = "";

        if (count($values) > 0) {
            if (count($values) > 20) {
                $buttons = "<button onclick='previousTable(`$id`)' disabled class='h-[36px] rounded-md hover:bg-zinc-100 disabled:opacity-50 px-2 text-[14px] max-md:hidden'> &lt; Précédent</button>";
                $nbButtons = ceil(count($values) / 20);
                for ($i = 1; $i <= $nbButtons && $i <= 5; $i++) {
                    $buttons .= "<button onclick='goToTable(`$id`, $i)' class='h-[36px] w-[36px] rounded-md hover:bg-zinc-100 duration-200 text-[14px] " . ($i === 1 ? "active" : "") . " [&.active]:drop-shadow-[16px] [&.active]:bg-white [&.active]:border [&.active]:border-zinc-300'>$i</button>";
                }
                if ($nbButtons > 5)
                    $buttons .= "<p>…</p>";
                $enabled = $nbButtons > 1 ? "" : "disabled";
                $buttons .= "<button onclick='nextTable(`$id`)' $enabled class='h-[36px] rounded-md hover:bg-zinc-100 disabled:opacity-50 px-2 text-[14px] max-md:hidden '>Suivant &gt;</button>";
            }
        }

        echo <<<HTML
<div class="flex flex-col w-full justify-center items-start overflow-hidden overflow-x-auto example">
<div class="flex gap-2 w-full mb-2">
    <input onkeydown="if(event.key === 'Enter')search('search-$i', '$id', $cols, '$empty', 'clear-$i')" type="text" id="search-$i" placeholder="Rechercher" class=" w-full bg-white border text-zinc-900 text-sm rounded-md focus:ring-zinc-500 focus:border-zinc-500 block px-2.5 py-2 ">
HTML;
        echo "<button onclick=\"search('search-$i', '$id', $cols, '$empty', 'clear-$i')\" class=\"text-white bg-zinc-700 hover:bg-zinc-800 focus:ring-4 focus:outline-none focus:ring-zinc-300 font-medium rounded-md text-sm px-2.5 py-2.5 text-center\">";
        echo I_Search::render("w-5 h-5");
        echo "</button>";
        echo "<button style=\"display: none\" id=\"clear-$i\" onclick=\"clearInput('search-$i');search('search-$i', '$id', $cols, '$empty', 'clear-$i')\" class=\"text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-md text-sm px-2.5 py-2 text-center\">";
        echo I_Close::render("w-5 h-5");
        echo "</button>";
        echo <<<HTML
</div>
<div class="rounded-md border w-full overflow-hidden overflow-x-auto example">
<table class=" divide-y-[1px] divide-zinc-200 bg-white text-sm w-full example" id="$id">
        <thead class="ltr:text-left rtl:text-right">
        <tr>
HTML;
        for ($i = 0; $i < count($columns); $i++) {
            echo '<th class="select-none cursor-pointer whitespace-nowrap px-2 py-2 font-medium text-left text-zinc-900 hover:bg-gray-100" onclick="sortTable(\'' . $id . '\', this, ' . $i . ')">
				<div class="flex justify-between items-center">
				' . $columns[$i] . I_Chevron::render("w-4 h-4 fa-solid fa-sort ") . '
				</div>                
            </th>';
        }


        echo <<<HTML
        </tr>
        </thead>

        <tbody class="divide-y-[1px] divide-zinc-100 overflow-hidden w-full ">
HTML;
        if (count($values) > 0) {
            for ($i = 0; $i < count($values); $i++) {
                $hidden = $i >= 20 ? "hidden" : "";
                echo <<<HTML
<tr class="odd:bg-zinc-50 $hidden">
HTML;
                $callback($values[$i]);
                echo <<<HTML
</tr>
HTML;
            }
        }
        $style = count($values) > 0 ? "display: none" : '';
        echo <<<HTML
</tbody>
    </table>
    </div>
    <div class="w-full flex justify-center gap-2 pt-4" id="$id-pagination">
    $buttons
</div>
    <p id="$empty" style="$style" class="pl-4 pt-2 text-zinc-800">Aucun résultats</p>
</div>
HTML;
    }

    public static function button($link, $text = "Voir plus", $color = ""): void
    {
        $c = "bg-zinc-800 hover:bg-zinc-900";
        if ($color == "red")
            $c = "bg-red-600 hover:bg-red-700";
        elseif ($color == "green")
            $c = "bg-green-600 hover:bg-green-700";
        self::cell(<<<HTML
	<a href="$link"
	   class="inline-block rounded  px-4 py-2 text-xs font-medium text-white $c">$text</a>
HTML
        );
    }

    public static function cell($value): void
    {
        $color = "text-zinc-900";
        if ($value == null || $value == "") {
            $value = "(Vide)";
            $color = "text-zinc-400";
        }
        echo "<td class=\"whitespace-nowrap px-4 py-2 font-medium $color\">";
        echo $value;
        echo "</td>";
    }

    public static function chip($value, $color): void
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

    public static function link($link): void
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
        $p = str_replace(' ', '', $phone);
        $phone = self::formatPhone($phone);
        self::cell(($phone == null || $phone == "") ? "" : <<<HTML
<a href="tel:$p">$phone</a>
HTML
        );
    }

    private static function formatPhone($phone)
    {
        if (strlen($phone) % 2 != 0)
            $phone = '0' . $phone;
        $phone = str_replace(' ', '', $phone);
        $phone = str_split($phone, 2);
        return implode(' ', $phone);
    }

    public static function pdfLink($link, $text)
    {
        self::cell(($link == null || $link == "") ? "" : <<<HTML
        <a target="_blank" href="$link" class="text-blue-600"> $text </a>
    HTML
        );
    }


}