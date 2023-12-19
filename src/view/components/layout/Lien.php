<?php

namespace app\src\view\components\layout;

use app\src\model\View;
use app\src\view\components\ComponentInterface;

class Lien implements ComponentInterface
{
    public static function render(array $params): void
    {
        $href = $params['href'] ?? '';
        $nom = $params['nom'] ?? '';
        $svg = $params['svg'] ?? '';

        if (!isset($_COOKIE['sidebar_open']) || $_COOKIE['sidebar_open'] === 'true') $isOpen = true; else $isOpen = false;

        echo "<a href=\"/$href\" class=\"lienBlock py-2 rounded-[8px] duration-200 ease-out flex flex-row items-center justify-start gap-2 text-left hover:bg-zinc-100 group relative";
        if (View::getCurrentSection() === $nom) echo " bg-zinc-100 text-black hover:text-blue-600";
        else {
            if ($href == 'logout') echo "bg-white text-red-500";
            else echo " bg-white text-zinc-400";
        }
        if ($isOpen) echo " px-2.5 w-full"; else echo " px-2.5 w-10";
        echo "\">";
        echo "<span class='ml-0.5'> $svg </span> <span class=\" lienNom whitespace-nowrap";
        if (!$isOpen) echo " hidden\">"; else echo " \">";
        echo "$nom </span>";
        echo "<div class=\" lienTooltip whitespace-nowrap opacity-0 text-white text-left z-50 group-hover:visible invisible group-hover:opacity-100 flex flex-col items-start justify-center duration-300 transition-all transform translate-x-2 group-hover:translate-x-6 absolute relative left-1/2 px-2 h-8 bg-zinc-800 drop-shadow border rounded-[8px]\"> $nom 
            <div class=\"flex h-6 w-6 flex-col items-center absolute  duration-150 left-0 top-1/2 transform -translate-y-1/2 -translate-x-2.5\">
                <div  class=\" h-3 w-1 rounded-full duration-150 bg-zinc-800 rotate-[20deg] -translate-x-0.5  translate-y-0.5\"></div>
                <div  class=\" h-3 w-1 rounded-full duration-150 bg-zinc-800 -rotate-[20deg] -translate-x-0.5 -translate-y-0.5\"></div>
            </div>
            </div>";
        echo "</a>";
    }
}