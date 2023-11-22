<?php

namespace app\src\core\components\dashboard_blocks;

use app\src\core\components\ComponentInterface;

/** @var array $data */
class LineChartBlock implements ComponentInterface
{
    private array $data;
    private mixed $maxValue;
    private string $chartId;
    private int $width;
    private int $height;
    private int $padding;
    private string $row_1;
    private string $row_2;
    private string $color;
    private bool $legend;

    public function __construct(array $params)
    {
        $this->data = $params['data'] ?? [];
        $this->row_1 = $params['row_1'] ?? 'Row1';
        $this->row_2 = $params['row_2'] ?? 'Row2';
        $this->color = $params['color'] ?? '#8f34eb';
        $this->legend = $params['legend'] ?? true;
        $this->width = $params['size'][0] ?? 300;
        $this->height = $params['size'][1] ?? 150;
        $this->padding = $params['size'][2] ?? 20;

        $columnValues = array_column($this->data, $this->row_1);
        $this->maxValue = $columnValues ? max($columnValues) : 0;
        $this->chartId = uniqid('linechart_', true);
        $this->render();
    }

    public function render(): void
    {
        echo <<<EOT
            <div class="border rounded-[8px] shadow w-full  bg-white p-4 relative">
            <button class="w-5 h-5 border hover:scale-105 duration-75 bg-green-500 backdrop-blur-md shadow absolute top-0 right-0 rounded-full translate-x-2 -translate-y-2 flex items-center justify-center"><span class="text-white text-xl">+</span></button>
        EOT;

        $innerWidth = $this->width - 2 * $this->padding;
        $innerHeight = $this->height - 2 * $this->padding;

        $points = [];
        $count = count($this->data);
        if ($count <= 1) $increment = $innerWidth;
        else $increment = $innerWidth / (count($this->data) - 1);
        $index = 0;

        foreach ($this->data as $row) {
            $percentageHeight = $this->calculatePercentage($row[$this->row_1]);
            $x = $index * $increment + $this->padding;
            $y = $innerHeight - ($percentageHeight / 100 * $innerHeight) + $this->padding;
            $points[] = "$x,$y";
            $index++;
        }

        $pointsString = implode(' ', $points);

        $xAxisYPosition = $this->height - $this->padding;
        $yAxisXPosition = $this->padding;

        $filledPathPoints = 'M' . ($this->padding) . ',' . ($xAxisYPosition);

        foreach ($points as $point) $filledPathPoints .= ' L' . $point;
        $filledPathPoints .= ' L' . ($this->width - $this->padding) . ',' . ($xAxisYPosition);
        $filledPathPoints .= ' L' . ($this->padding) . ',' . ($xAxisYPosition) . ' Z';

        $gradientId = $this->chartId . "_gradient";

        echo <<<EOT
            <svg id="$this->chartId" width="100%" height="100%" viewBox="0 0 $this->width $this->height" class="line-chart">
            <defs>
            <linearGradient id="$gradientId" x1="0%" y1="100%" x2="0%" y2="0%">
                <stop offset="10%" style="stop-opacity:0.15;stop-color:$this->color" />
                <stop offset="100%" style="stop-opacity:0.8;stop-color:$this->color" />
            </linearGradient>
            </defs>
            <path class='drop-shadow' d="$filledPathPoints" fill="url(#$gradientId)" />
            <polyline class='drop-shadow' fill="none" stroke-linecap='round' stroke-width="2" points="$pointsString" stroke="$this->color" />
        EOT;

        if ($this->legend) {
            $labelIncrement = ($this->height - 2 * $this->padding) / $this->maxValue - 1;
            for ($i = 0; $i <= $this->maxValue + 3; $i += $this->maxValue / 10) {
                $yPos = $this->height - $this->padding - $labelIncrement * $i;
                echo "<line class='drop-shadow' x1=\"" . ($this->padding) . "\" y1=\"$yPos\" x2=\"" . ($this->width) . "\" y2=\"$yPos\" stroke-width=\"1\" stroke=\"$this->color\" opacity=\"0.075\" />";
            }

            $xOffset = $this->padding + $increment;
            for ($i = 0; $i < (count($this->data)); $i++) {
                echo "<line class='drop-shadow' x1=\"$xOffset\" y1=\"0\" x2=\"$xOffset\" y2=\"$this->height\" stroke-width=\"1\" stroke=\"$this->color\" opacity=\"0.075\" />";
                $xOffset += $increment;
            }
        }

        $temp1 = $this->height - $this->padding;
        echo "<rect x=\"" . ($this->padding - 3) . "\" y=\"$temp1\" width=\"$this->width\" height=\"$this->padding\" fill=\"#fff\" />";
        echo "<line class='drop-shadow' x1=\"$this->padding\" y1=\"$temp1\" x2=\"$this->width\" y2=\"$temp1\" stroke-width=\"1\"  stroke=\"$this->color\" />";

        if ($this->legend) {
            foreach ($points as $point) {
                list($x, $y) = explode(',', $point);
                echo "<line class='drop-shadow' x1='$x' y1='$xAxisYPosition' x2='$x' y2='$y' stroke-width='1' stroke-dasharray='5,5' stroke=$this->color />";
                echo "<line class='drop-shadow' x1='$yAxisXPosition' y1='$y' x2='$x' y2='$y' stroke-width='1' stroke-dasharray='5,5' stroke=$this->color />";
            }
        }

        $index = 0;
        foreach ($this->data as $row) {
            $x = $index * $increment + $this->padding;
            $yLabelPosition = $xAxisYPosition + 15;
            $percentageHeight = $this->calculatePercentage($row[$this->row_1]);
            $y = $innerHeight - ($percentageHeight / 100 * $innerHeight) + $this->padding;
            if ($this->legend) {
                if ($this->row_2 === 'mois') {
                    $date = explode('-', $row[$this->row_2]);
                    $month = $date[1];
                    $year = substr($date[0], 2);
                    echo "<text class='drop-shadow dateLineChart' font-size='10' x='$x' y='$yLabelPosition' text-anchor='middle'>$month/$year</text>";
                } else {
                    echo "<text class='drop-shadow' font-size='10' x='$x' y='$yLabelPosition' text-anchor='middle'>" . $row[$this->row_2] . "</text>";
                }
                $yAxisValue = round($row[$this->row_1], 2);
                echo "<text class='drop-shadow' x='" . ($yAxisXPosition - 5) . "' y='$y' font-size='10' text-anchor='end' alignment-baseline='middle'>$yAxisValue</text>";
                echo "<circle class='drop-shadow' cx='$x' cy='$y' r='3' fill=$this->color />";
            }
            $index++;
        }

        echo <<<EOT
            </svg>
            </div>
        EOT;
    }

    private function calculatePercentage($value): float
    {
        if ($this->maxValue == 0) return 0;
        else return ($value / $this->maxValue) * 100;
    }

}
