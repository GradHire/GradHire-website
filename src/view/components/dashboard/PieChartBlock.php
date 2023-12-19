<?php

namespace app\src\view\components\dashboard;

use app\src\view\components\ComponentInterface;

class PieChartBlock implements ComponentInterface
{

    public static function render(array $params): void
    {
        $data = $params['data'] ?? [];
        $row_1 = $params['row_1'] ?? 'Row1';
        $row_2 = $params['row_2'] ?? 'Row2';
        $total = array_sum(array_column($data, $row_1)) ?? 0;
        $colors = $params['colors'] ?? ["#3d348b", "#7678ed", "#f7b801", "#f18701", "#f35b04", "#f542ad", "#4296f5", "#42f54e", "#f5df42"];

        echo '<div class="w-full flex flex-col gap-1.5 min-w-[300px] max-h-[380px] rounded-[8px] shadow p-4 bg-white border relative items-start justify-start">';

        $pieChartStyle = self::createPieChartStyle($data, $row_1, $total, $colors);
        ob_start();
        ?>
        <div class="items-start flex flex-col justify-between h-full w-full">
            <?php TitleBlock::render(['title' => 'Distribution', 'subtitle' => 'Des offres par domaine']); ?>
            <div class="flex flex-col gap-8 items-center justify-around w-full">
                <div class="pie-chart shadow border min-w-[185px] min-h-[185px] max-w-[185px] max-h-[185px]"
                     style="width: 200px; height: 200px; border-radius: 50%; background: conic-gradient(<?= $pieChartStyle ?>);"></div>
                <div class="pie-chart-legend uppercase text-[12px] grid grid-cols-3 gap-y-2 gap-x-4">
                    <?php foreach ($data as $index => $row): ?>
                        <div class="flex flex-row gap-1">
                    <span class="w-[14px] h-[14px] rounded-[2px] shadow"
                          style="background-color: <?= $colors[$index] ?? '#000'; ?>;
                                  "></span><span> <?= ' - ' . htmlspecialchars(substr($row[$row_2], 0, 3) . '…' . substr($row[$row_2], -3)) ?? 'Autre' ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
        $content = ob_get_clean();
        echo $content;
        echo '</div>';
    }

    private static function createPieChartStyle(mixed $data, mixed $row_1, float|int $total, mixed $colors): string
    {
        $gradientPieces = [];
        $offset = 0;
        foreach ($data as $index => $row) {
            $value = $row[$row_1];
            $percentage = round(($value / $total) * 100, 2);
            $color = $colors[$index] ?? "hsl(" . rand(0, 360) . ", 100%, 50%)";
            if ($percentage > 0) {
                $gradientPieces[] = "$color 0 $offset%";
                $offset += $percentage;
                $gradientPieces[] = "$color $offset%";
            }
        }
        return implode(', ', $gradientPieces);
    }
}

