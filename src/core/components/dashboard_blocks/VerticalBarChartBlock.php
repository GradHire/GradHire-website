<?php

namespace app\src\core\components\dashboard_blocks;

use app\src\core\components\ComponentInterface;

class VerticalBarChartBlock implements ComponentInterface
{
    private array $data;
    private mixed $maxValue;
    private string $chartId;
    private int $width;
    private int $height;
    private int $padding;
    private int $rounding;
    private string $row_1;
    private string $row_2;
    private string $color;

    public function __construct(array $params)
    {
        $this->data = $params['data'] ?? [];
        $this->row_1 = $params['row_1'] ?? 'Row1';
        $this->row_2 = $params['row_2'] ?? 'Row2';
        $this->color = $params['color'] ?? '#f56642';
        $this->width = $params['size'][0] ?? 600;
        $this->height = $params['size'][1] ?? 150;
        $this->padding = $params['size'][2] ?? 20;
        $this->rounding = $params['size'][3] ?? 10;

        $columnValues = array_column($this->data, $this->row_1);
        $this->maxValue = $columnValues ? max($columnValues) : 1;
        $this->chartId = uniqid('barchart_', true);
        $this->render();
    }

    public function render(): void
    {
        $barWidth = ($this->width - $this->padding - 1) / count($this->data);
        $increment = $this->height / $this->maxValue;

        $gradientId = $this->chartId . "_gradient";
        echo <<<EOT
<div class="border rounded-[8px] shadow w-full  bg-white p-4 relative">
<button class="w-5 h-5 border hover:scale-105 duration-75 bg-green-500 backdrop-blur-md shadow absolute top-0 right-0 rounded-full translate-x-2 -translate-y-2 flex items-center justify-center"><span class="text-white text-xl">+</span></button>

EOT;

        echo "<svg id=\"$this->chartId\" width=\"100%\" height=\"100%\" viewBox=\"0 0 $this->width $this->height\" class=\"svg-bar-chart\">";

        echo <<<EOT
        <defs>
            <linearGradient id="$gradientId" x1="0%" y1="100%" x2="0%" y2="0%">
                <stop offset="0%" style="stop-opacity:1 ;stop-color:$this->color" />
                <stop offset="100%" style="stop-opacity:0.4;stop-color:$this->color" />
            </linearGradient>
        </defs>
        EOT;

        $labelIncrement = $this->height / $this->maxValue;
        for ($i = 0; $i <= $this->maxValue; $i += $this->maxValue / 10) {
            $yPos = $this->height - $this->padding - $labelIncrement * $i;
            echo "<line x1=\"" . ($this->padding) . "\" y1=\"$yPos\" x2=\"" . ($this->width) . "\" y2=\"$yPos\" stroke-width=\"1\" stroke=\"$this->color\" opacity=\"0.075\" />";
        }

        $xOffset = $this->padding + $barWidth / 2;
        for ($i = 0; $i < (count($this->data) * 2); $i++) {
            echo "<line x1=\"$xOffset\" y1=\"0\" x2=\"$xOffset\" y2=\"$this->height\" stroke-width=\"1\" stroke=\"$this->color\" opacity=\"0.075\" />";
            $xOffset += $barWidth / 2;
        }

        $xOffset = $this->padding;

        foreach ($this->data as $row) {
            $barHeight = $increment * $row[$this->row_1];
            $yPos = $this->height - $barHeight + $this->rounding;
            $barHeightNew = $barHeight - $this->padding;
            echo <<<EOT
            <rect class="barChartRect" x="$xOffset" y="$yPos" width="$barWidth" stroke="$this->color" height="$barHeightNew" fill="url(#$gradientId)" rx="$this->rounding" ry="$this->rounding" />
            EOT;
            echo "<line x1=\"" . ($this->padding) . "\" y1=\"$yPos\" x2=\"" . ($xOffset + $barWidth / 2) . "\" y2=\"$yPos\" stroke-width=\"1\" stroke-dasharray=\"5,5\" stroke=\"$this->color\" />";
            echo "<circle cx=\"" . ($xOffset + $barWidth / 2) . "\" cy=\"$yPos\" r=\"3\" fill=\"$this->color\" />";
            $xOffset += $barWidth;
        }

        $labelIncrement = $this->height / $this->maxValue;
        for ($i = 0; $i <= $this->maxValue; $i += $this->maxValue / 10) {
            $yPos = $this->height - $this->padding - $labelIncrement * $i;
            echo "<text x=\"" . ($this->padding / 2) . "\" y=\"$yPos\" font-size=\"10\" text-anchor=\"middle\">" . round($i, 1) . "</text>";
        }

        $xOffset = $this->padding + $barWidth / 2;
        $temp1 = $this->height - $this->padding;
        echo "<rect x=\"" . ($this->padding - 1) . "\" y=\"$temp1\" width=\"$this->width\" height=\"$this->padding\" fill=\"#fff\" />";
        echo "<line x1=\"$this->padding\" y1=\"$temp1\" x2=\"$this->width\" y2=\"$temp1\" stroke-width=\"1\"  stroke=\"$this->color\" />";

        foreach ($this->data as $row) {
            $domain = !empty($row[$this->row_2]) ? strtoupper($row[$this->row_2]) : 'AUTRE';
            echo "<text x=\"$xOffset\" y=\"" . ($this->height - 5) . "\" font-size=\"10\" text-anchor=\"middle\" fill=\"#27272a\" filter=\"url(#dropshadow)\">" . $domain . "</text>";
            $xOffset += $barWidth;
        }

        echo "</svg>";
        echo <<<EOT
</div>
EOT;

    }

}
