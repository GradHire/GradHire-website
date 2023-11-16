<?php

namespace app\src\core\components\charts;

class PieChart
{
    private $stats;
    private $total;
    private $colors;

    public function __construct(array $stats, $total, $colors = [])
    {
        $this->stats = $stats;
        $this->total = $total;
        $this->colors = $colors;
    }

    public function render()
    {
        $pieChartStyle = $this->createPieChartStyle();
        ob_start();
        ?>

        <div class="flex flex-row gap-2 items-center justify-around w-full">
            <div class="pie-chart shadow "
                 style="width: 200px; height: 200px; border-radius: 50%; background: conic-gradient(<?= $pieChartStyle ?>);"></div>
            <div class="pie-chart-legend uppercase text-[12px] grid grid-cols-1 gap-y-2 gap-x-2">
                <?php foreach ($this->stats as $index => $row): ?>
                    <div class="flex flex-row gap-1">
                    <span class="w-[14px] h-[14px] rounded-[2px] shadow "
                          style="background-color: <?= $this->colors[$index] ?? '#000'; ?>;
                                  "></span> <?= ' - ' . htmlspecialchars($row['thematique'] ?? 'Autre') ?>
                        : <?= $row['nombreoffres'] ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        $content = ob_get_clean();
        echo $content;
    }

    private function createPieChartStyle(): string
    {
        $gradientPieces = [];
        $offset = 0;

        foreach ($this->stats as $index => $row) {
            $value = $row['nombreoffres'];
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

