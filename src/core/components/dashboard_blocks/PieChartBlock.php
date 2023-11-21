<?php

namespace app\src\core\components\dashboard_blocks;

use app\src\core\components\ComponentInterface;

class PieChartBlock implements ComponentInterface
{
    private array $data;
    private string $row_1;
    private string $row_2;
    private int $total;
    private array $colors;

    public function __construct(array $params)
    {
        $this->data = $params['data'] ?? [];
        $this->row_1 = $params['row_1'] ?? 'Row1';
        $this->row_2 = $params['row_2'] ?? 'Row2';
        $this->total = array_sum(array_column($this->data, $this->row_1)) ?? 0;
        $this->colors = $params['colors'] ?? ["#3d348b", "#7678ed", "#f7b801", "#f18701", "#f35b04", "#f542ad", "#4296f5", "#42f54e", "#f5df42"];
        $this->render();
    }

    public function render(): void
    {
        $pieChartStyle = $this->createPieChartStyle();
        ob_start();
        ?>
        <div class="flex flex-row gap-2 items-center justify-around w-full">
            <div class="pie-chart shadow drop-shadow" style="width: 200px; height: 200px; border-radius: 50%; background: conic-gradient(<?= $pieChartStyle ?>);"></div>
            <div class="pie-chart-legend uppercase text-[12px] grid grid-cols-1 gap-y-2 gap-x-2">
                <?php foreach ($this->data as $index => $row): ?>
                    <div class="flex flex-row gap-1">
                    <span class="w-[14px] h-[14px] rounded-[2px] shadow "
                          style="background-color: <?= $this->colors[$index] ?? '#000'; ?>;
                                  "></span><span class="drop-shadow"> <?= ' - ' . htmlspecialchars($row[$this->row_2] ?? 'Autre') ?>
                        : <?= $row[$this->row_1] ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <script>

        </script>
        <?php
        $content = ob_get_clean();
        echo $content;
    }

    private function createPieChartStyle(): string
    {
        $gradientPieces = [];
        $offset = 0;
        foreach ($this->data as $index => $row) {
            $value = $row[$this->row_1];
            $percentage = round(($value / $this->total) * 100, 2);
            $color = $this->colors[$index] ?? "hsl(" . rand(0, 360) . ", 100%, 50%)";
            if ($percentage > 0) {
                $gradientPieces[] = "$color 0 $offset%";
                $offset += $percentage;
                $gradientPieces[] = "$color $offset%";
            }
        }
        return implode(', ', $gradientPieces);
    }
}

