<?php

namespace app\src\view\components\ui;


use app\src\view\components\ComponentInterface;

class Separator implements ComponentInterface
{
    public static function render(array $params): void
    {
        $color = $params['color'] ?? 'zinc-200';
        $orientation = $params['orientation'] ?? 'horizontal';

        if ($orientation === 'vertical') {
            $width = $params['width'] ?? '[1px]';
            $height = $params['height'] ?? 'full';
        } else {
            $width = $params['width'] ?? 'full';
            $height = $params['height'] ?? '[1px]';
        }

        $output = '<div class="w-' . $width . ' h-' . $height . ' bg-' . $color . '"></div>';
        echo $output;
    }
}