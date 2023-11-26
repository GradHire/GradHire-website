<?php

namespace app\src\core\components;

use app\src\model\Application;
use app\src\model\View;

class Lien implements ComponentInterface
{
    private string $href;
    private string $nom;
    private string $svg;

    public function __construct(array $params)
    {
        $this->href = $params['href'] ?? '';
        $this->nom = $params['nom'] ?? '';
        $this->svg = $params['svg'] ?? '';
    }

    public function render(): void
    {
        echo <<<EOT
            <a href="$this->href" class="py-2.5 px-4 rounded-[8px] duration-300 ease-out w-full flex flex-row items-center justify-start gap-2 text-left hover:bg-zinc-100
        EOT;
        if (View::getCurrentSection() === $this->nom) echo ' bg-zinc-100 text-black '; else echo ' bg-white text-zinc-400 ';
        echo <<<EOT
             ">
            $this->svg
            $this->nom
            </a>
        EOT;
    }
}