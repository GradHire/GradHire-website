<?php

namespace app\src\view\components\layout;

use app\src\model\View;
use app\src\view\components\ComponentInterface;

class Lien implements ComponentInterface
{
    public static function render(array $params): void
    {
        $href = $params['href'] ?? '';
        $sectionTitle = $params['nom'] ?? '';
        $svg = $params['svg'] ?? '';
        $sidebarOpen = self::isSidebarOpen();

        $htmlParts = [];
        $htmlParts[] = "<a href=\"/$href\" class=\"lienBlock py-2 rounded-[8px] duration-200 ease-out flex flex-row items-center justify-start gap-2 text-left hover:bg-zinc-100 group relative";
        $htmlParts[] = (View::getCurrentSection() === $sectionTitle) ? " bg-zinc-100 text-black hover:text-blue-600" : (($href === 'logout') ? "bg-white text-red-500" : " bg-white text-zinc-400");
        $htmlParts[] = $sidebarOpen ? " px-2.5 w-full\"" : " px-2.5 w-9\"";
        $htmlParts[] = "<span> $svg </span> <span class=\"lienNom whitespace-nowrap";
        $htmlParts[] = !$sidebarOpen ? " hidden\"" : " \"";
        $htmlParts[] = "$sectionTitle </span>";
        $htmlParts[] = "<div class=\"lienTooltip whitespace-nowrap opacity-0 text-white text-left z-50 group-hover:visible invisible group-hover:opacity-100 flex flex-col items-start justify-center duration-300 transition-all transform translate-x-2 group-hover:translate-x-6 absolute relative left-1/2 px-2 h-8 bg-zinc-800 drop-shadow border rounded-[8px]\">$sectionTitle</div>";
        $htmlParts[] = "<div class=\"flex h-6 w-6 flex-col items-center absolute duration-150 left-0 top-1/2 transform -translate-y-1/2 -translate-x-2.5\"></div>";
        $htmlParts[] = "<div class=\"h-3 w-1 rounded-full duration-150 bg-zinc-800 rotate-[20deg] -translate-x-0.5  translate-y-0.5\"></div>";
        $htmlParts[] = "<div class=\"h-3 w-1 rounded-full duration-150 bg-zinc-800 -rotate-[20deg] -translate-x-0.5 -translate-y-0.5\"></div>";
        $htmlParts[] = "</div></a>";

        $html = join($htmlParts);
        echo $html;
    }

    private static function isSidebarOpen(): bool
    {
        return !isset($_COOKIE['sidebar_open']) || $_COOKIE['sidebar_open'] === 'true';
    }
}