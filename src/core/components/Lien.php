<?php

namespace app\src\core\components;

class Lien
{
    private string $href;
    private string $nom;

    public function __construct(string $href, string $nom)
    {
        $this->href = $href;
        $this->nom = $nom;
    }

    public function render(): string
    {
        return <<<HTML
        <a href="$this->href" class="flex items-center text-2xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800 border rounded-[8px] bg-white hover:bg-zinc-50 text-center w-full hover:underline p-4">$this->nom</a>
HTML;
    }
}