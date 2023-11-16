<?php

namespace app\src\core\components\charts;

class SVGBarChart
{
    private $data;
    private $maxValue;
    private $chartId;
    private $width;
    private $height;
    private $padding;
    private $colName1;
    private $colName2;
    private $legend;
    private $color;

    public function __construct(array $data, string $colName1, string $colName2, bool $legend, array $size, string $color)
    {
        $this->data = $data;
        $this->chartId = uniqid('barchart_', true);
        $this->colName1 = $colName1;
        $this->colName2 = $colName2;
        $columnValues = array_column($data, $colName2);
        $this->maxValue = $columnValues ? max($columnValues) : 1;
        $this->legend = $legend;
        $this->color = $color;
        $this->width = $size['width'];
        $this->height = $size['height'];
        $this->padding = $size['padding'];
    }

    public function render(): void
    {
        $barWidth = ($this->width - 2 * $this->padding) / count($this->data);
        $increment = $this->height / $this->maxValue;

        $gradientId = $this->chartId . "_gradient";

        echo "<svg id=\"{$this->chartId}\" width=\"100%\" height=\"100%\" viewBox=\"0 0 {$this->width} {$this->height}\" class=\"svg-bar-chart\">";

        echo <<<EOT
        <defs>
            <linearGradient id="$gradientId" x1="0%" y1="100%" x2="0%" y2="0%">
                <stop offset="0%" style="stop-opacity:1 ;stop-color:{$this->color}" />
                <stop offset="100%" style="stop-opacity:0.4;stop-color:{$this->color}" />
            </linearGradient>
        </defs>
        EOT;
        $xOffset = $this->padding;
        foreach ($this->data as $row) {
            $barHeight = $increment * $row[ $this->colName2];
            $yPos = $this->height + $this->padding - $barHeight;
            echo <<<EOT
            <rect x="{$xOffset}" y="{$yPos}" width="{$barWidth}" height="{$barHeight}" fill="url(#$gradientId)" rx="10" ry="10" />
            EOT;
            echo "<line x1=\"" . ($this->padding) . "\" y1=\"{$yPos}\" x2=\"" . ($xOffset + $barWidth / 2) . "\" y2=\"{$yPos}\" stroke-width=\"1\" stroke-dasharray=\"5,5\" stroke=\"{$this->color}\" />";
            echo "<circle cx=\"" . ($xOffset + $barWidth / 2) . "\" cy=\"{$yPos}\" r=\"3\" fill=\"{$this->color}\" />";
            $xOffset += $barWidth;
        }

        $labelIncrement = $this->height / $this->maxValue;
        for ($i = 0; $i <= $this->maxValue; $i += $this->maxValue / 10) {
            $yPos = $this->height - $this->padding - $labelIncrement * $i;
            echo "<text x=\"" . ($this->padding / 2) . "\" y=\"{$yPos}\" font-size=\"10\" text-anchor=\"middle\">" . round($i, 2) . "</text>";
        }

        $xOffset = $this->padding + $barWidth / 2;
        foreach ($this->data as $row) {
            $domain = !empty($row[ $this->colName1]) ? strtoupper($row[ $this->colName1]) : 'AUTRE';
            echo "<text x=\"{$xOffset}\" y=\"" . ($this->height - 5) . "\" font-size=\"10\" text-anchor=\"middle\" fill=\"white\" stroke=\"white\" stroke-width=\"0.3\">" . $domain . "</text>";
            $xOffset += $barWidth;
        }

        echo "</svg>";
    }

}
