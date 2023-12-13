<?php

namespace app\src\view\dashboard\components\dashboard_blocks;

class TitleBlock
{
    public static function render(array $params): void
    {
        $title = $params['title'] ?? '';
        $subtitle = $params['subtitle'] ?? '';

        if ($subtitle !== '') {
            echo <<<EOT
            <div class="flex flex-col justify-start items-start">
                <h2 class="text-md font-bold text-zinc-700">{$title}</h2>
                <p class="text-sm text-zinc-500">{$subtitle}</p>
            </div>
        EOT;
        } else {
            echo <<<EOT
                <h2 class="text-md font-bold text-zinc-700">{$title}</h2>
            EOT;
        }
    }
}