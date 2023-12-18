<?php

namespace app\src\view\components\dashboard;

use app\src\view\components\ComponentInterface;

class TitleBlock implements ComponentInterface
{
    const BASIC_TYPE = "text-md font-bold text-zinc-700";
    const SUB_TYPE = "text-sm text-zinc-500";
    const DIV_CONTENT = "flex flex-col justify-start items-start";

    public static function render(array $params): void
    {
        $title = $params['title'] ?? '';
        $subtitle = $params['subtitle'] ?? '';
        $basicType = self::BASIC_TYPE;
        $subType = self::SUB_TYPE;
        $divContent = self::DIV_CONTENT;

        if ($subtitle !== '') echo self::renderWithTitleAndSubtitle($title, $subtitle, $basicType, $subType, $divContent);
        else echo self::renderWithTitle($title, $basicType);
    }

    private static function renderWithTitleAndSubtitle(
        string $title,
        string $subtitle,
        string $basicType,
        string $subType,
        string $divContent
    ): string
    {
        return <<<EOT
            <div class="$divContent">
                <h2 class="$basicType">$title</h2>
                <p class="$subType">$subtitle</p>
            </div>
        EOT;
    }

    private static function renderWithTitle(string $title, string $basicType): string
    {
        return <<<EOT
            <h2 class="$basicType">$title</h2>
        EOT;
    }
}