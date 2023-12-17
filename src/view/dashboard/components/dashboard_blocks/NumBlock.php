<?php

namespace app\src\view\dashboard\components\dashboard_blocks;

class NumBlock
{
    public static function render(array $params): void
    {
        $title = $params['title'] ?? 'Title';
        $value = (int)($params['value'] ?? 0);

        echo static::generateHtml($title, $value);
    }

    protected static function generateHtml(string $title, int $value): string
    {
        return "<div class=\"px-4 py-2 w-full min-w-[150px] max-h-[75px] border border-zinc-200 shadow bg-white rounded-[8px] flex flex-col justify-around items-start relative\">
            <p class=\"text-xs font-medium text-zinc-400\">$title</p>
            <p class=\"text-3xl font-bold text-zinc-800\"><span class=\"counter\" data-target=\"$value\">0</span></p>
        </div>";
    }
}
