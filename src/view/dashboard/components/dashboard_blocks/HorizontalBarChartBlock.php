<?php

namespace app\src\view\dashboard\components\dashboard_blocks;

class HorizontalBarChartBlock
{
    public static function render(array $params): void
    {
        $data = $params['data'] ?? [];
        $row_1 = $params['row_1'] ?? 'Row1';
        $row_2 = $params['row_2'] ?? 'Row2';
        $colors = $params['colors'] ?? ['#add7f6', '#87bfff', '#2667ff', '#3b28cc'];
        $columnValues = array_column($data, $row_1);
        $maxValue = $columnValues ? max($columnValues) : 0;

        $color1 = $colors[0];
        $color2 = $colors[1];
        $color3 = $colors[2];
        $color4 = $colors[3];

        echo '<div class="w-full min-w-[300px] max-h-[150px] flex flex-col gap-1.5 rounded-[8px] shadow p-4 bg-white border relative">';
        TitleBlock::render(['title' => 'Domaines', 'subtitle' => 'Top 3 des plus demandés']);
        foreach ($data as $row) {
            $percentage = self::calculatePercentage($row[$row_1], $maxValue);
            $domain = !empty($row[$row_2]) ? $row[$row_2] : 'Autre';
            echo <<<EOT
                    <div class="rounded-md relative overflow-hidden shadow" style="background: linear-gradient(90deg, $color1 0%, $color2 100%);">
                        <div class="h-[18px] rounded-md animated-bar shadow-md"
                             style="width: 0; transition: width 1s ease-in-out; background: linear-gradient(90deg, $color3 0%, $color4 100%);"
                             data-percentage="$percentage">
                        </div>
                        <p class="absolute left-0 top-0 ml-2 text-white drop-shadow uppercase text-[12px]">$domain</p>
                        <p class="absolute right-0 top-0 mr-2 text-white drop-shadow uppercase text-[12px]">{$row[$row_1]}</p>
                    </div>
            EOT;
        }
        echo '</div>';
    }

    private static function calculatePercentage(mixed $row_1, mixed $maxValue)
    {
        if ($maxValue == 0) return 0;
        else return ($row_1 / $maxValue) * 100;
    }
}



