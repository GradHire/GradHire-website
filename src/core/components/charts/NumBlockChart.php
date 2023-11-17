<?php

namespace app\src\core\components\charts;

class NumBlockChart
{
    private $title;
    private $value;
    private $stats;
    private $colName1;
    private $colName2;
    private $color;

    public function __construct($title, $value, $stats, $colName1, $colName2, $color)
    {
        $this->title = $title;
        $this->value = (int)$value;
        $this->stats = $stats;
        $this->colName1 = $colName1;
        $this->colName2 = $colName2;
        $this->color = $color;
    }

    public function render(): void
    {
        $title = $this->title;
        $value = $this->value;

        echo <<<EOT
<div class="p-2 min-w-[200px] w-full min-h-[100px] max-h-[100px] max-w-[200px] flex flex-row justify-between items-center">
<div class="flex flex-col justify-between h-[80px] items-start">
    <p class="text-md font-medium text-zinc-400">$title</p>
    <div class="flex flex-col justify-start items-start w-4 translate-y-1">
    <p class="text-2xl font-medium text-zinc-800 leading-5 text-left"><span class="counter" data-target="$value">0</span></p>
    <p class="text-xs font-thin text-zinc-400 text-left">Example</p>
    </div>
    </div>
<div class="w-20 h-20">
EOT;
        $size = [
            'width' => 80,
            'height' => 80,
            'padding' => 0
        ];
        $lineChart = new LineChart($this->stats,$this->colName1,$this->colName2, false, $size, $this->color);
        $lineChart->render();
        echo <<<EOT
</div>
        </div>
EOT;
    }
}
