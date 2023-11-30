<?php

namespace app\src\core\components;

use app\src\model\View;

class Lien implements ComponentInterface
{
    private string $href;
    private string $nom;
    private string $svg;
    private bool $isOpen;

    public function __construct(array $params)
    {
        $this->href = $params['href'] ?? '';
        $this->nom = $params['nom'] ?? '';
        $this->svg = $params['svg'] ?? '';

        if (!isset($_COOKIE['sidebar_open']) || $_COOKIE['sidebar_open'] === 'true') $this->isOpen = true; else $this->isOpen = false;
    }

    public function render(): void
    {
        echo "<a href=\"$this->href\" class=\"lienBlock py-2.5 rounded-[8px] duration-200 ease-out flex flex-row items-center justify-start gap-2 text-left hover:bg-zinc-100 group relative";
        if (View::getCurrentSection() === $this->nom) echo " bg-zinc-100 text-black"; else echo " bg-white text-zinc-400";
        if ($this->isOpen) echo " px-2.5 w-full"; else echo " px-2.5 w-9";
        echo "\">";
        echo "<span> $this->svg </span> <span class=\" lienNom ";
        if (!$this->isOpen) echo " hidden\">"; else echo " \">";
        echo "$this->nom </span>";
        echo "<div class=\" lienTooltip opacity-0 text-black z-50 group-hover:visible invisible group-hover:opacity-100 flex flex-col items-center justify-around duration-300 transition-all transform translate-x-16 group-hover:translate-x-20 absolute right-0 w-16 h-8 bg-white drop-shadow border rounded-[8px]\"> $this->nom </div>";
        echo "</a>";
    }
}