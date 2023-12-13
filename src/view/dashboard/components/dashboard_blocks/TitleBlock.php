<?php

namespace app\src\view\dashboard\components\dashboard_blocks;

use app\src\core\components\ComponentInterface;

class TitleBlock implements ComponentInterface
{
    private string $title;
    private string $subtitle;

    public function __construct(array $params)
    {
        $this->title = $params['title'] ?? '';
        $this->subtitle = $params['subtitle'] ?? '';
        $this->render();
    }

    public function render(): void
    {

        if ($this->subtitle !== '') {
            echo <<<EOT
            <div class="flex flex-col justify-start items-start">
                <h2 class="text-md font-bold text-zinc-700">{$this->title}</h2>
                <p class="text-sm text-zinc-500">{$this->subtitle}</p>
            </div>
        EOT;
        } else {
            echo <<<EOT
                <h2 class="text-md font-bold text-zinc-700">{$this->title}</h2>
            EOT;
        }
    }
}