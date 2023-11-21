<?php

namespace app\src\core\components\dashboard_blocks;

use app\src\core\components\ComponentInterface;

class HorizontalBarChartBlock implements ComponentInterface
{
    private array $data;
    private string $maxValue;
    private string $row_1;
    private string $row_2;
    private array $colors;

    public function __construct(array $params)
    {
        $this->data = $params['data'] ?? [];
        $this->row_1 = $params['row_1'] ?? 'Row1';
        $this->row_2 = $params['row_2'] ?? 'Row2';
        $this->colors = $params['colors'] ?? ['#add7f6', '#87bfff', '#2667ff', '#3b28cc'];
        $columnValues = array_column($this->data, $this->row_1);
        $this->maxValue = $columnValues ? max($columnValues) : 0;
        $this->render();
    }

    public function render(): void
    {
        $color1 = $this->colors[0];
        $color2 = $this->colors[1];
        $color3 = $this->colors[2];
        $color4 = $this->colors[3];

        foreach ($this->data as $row) {
            $percentage = $this->calculatePercentage($row[$this->row_1]);
            $domain = !empty($row[$this->row_2]) ? $row[$this->row_2] : 'Autre';
            echo <<<EOT
                <div class="w-full flex flex-col">
                    <div class="rounded-md relative overflow-hidden shadow" style="background: linear-gradient(90deg, $color1 0%, $color2 100%);">
                        <div class="h-[18px] rounded-md animated-bar shadow-md"
                             style="width: 0; transition: width 1s ease-in-out; background: linear-gradient(90deg, $color3 0%, $color4 100%);"
                             data-percentage="{$percentage}">
                        </div>
                        <p class="absolute left-0 top-0 ml-2 text-white drop-shadow uppercase text-[12px]">$domain</p>
                        <p class="absolute right-0 top-0 mr-2 text-white drop-shadow uppercase text-[12px]">{$row[$this->row_1]}</p>
                    </div>
                </div>
            EOT;
        }
    }

    private function calculatePercentage($value): float|int
    {
        if ($this->maxValue == 0) return 0;
        else return ($value / $this->maxValue) * 100;
    }
}



