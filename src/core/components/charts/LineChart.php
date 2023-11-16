<?php

namespace app\src\core\components\charts;

/** @var array $data */
class LineChart
{
    private $data;
    private $maxValue;
    private $chartId;
    private $padding;
    private $colName1;
    private $colName2;
    private $legend;
    private $color;
    private $width;
    private $height;

    public function __construct(array $data, string $colName1, string $colName2, bool $legend, array $size, string $color)
    {
        $this->data = $data;
        $this->colName1 = $colName1;
        $this->colName2 = $colName2;
        $this->chartId = uniqid('linechart_', true);
        $columnValues = array_column($data, 'nombrecandidatures');
        $this->maxValue = $columnValues ? max($columnValues) : 0;
        $this->legend = $legend;
        $this->color = $color;
        $this->width = $size['width'];
        $this->height = $size['height'];
        $this->padding = $size['padding'];
    }

    public function getChartId(): string
    {
        return $this->chartId;
    }

    public function render(): void
    {

        $viewBoxWidth = $this->width;
        $viewBoxHeight = $this->height;

        $innerWidth = $viewBoxWidth - 2 * $this->padding;
        $innerHeight = $viewBoxHeight - 2 * $this->padding;

        $points = [];
        $count = count($this->data);
        if ($count <= 1) $increment = $innerWidth;
        else $increment = $innerWidth / (count($this->data) - 1);
        $index = 0;


        foreach ($this->data as $row) {
            $percentageHeight = $this->calculatePercentage($row[$this->colName2]);
            $x = $index * $increment + $this->padding;
            $y = $innerHeight - ($percentageHeight / 100 * $innerHeight) + $this->padding;

            $points[] = "{$x},{$y}";
            $index++;
        }

        $pointsString = implode(' ', $points);

        $xAxisYPosition = $viewBoxHeight - $this->padding;
        $yAxisXPosition = $this->padding;

        $filledPathPoints = 'M' . ($this->padding) . ',' . ($xAxisYPosition);

        foreach ($points as $point) {
            $filledPathPoints .= ' L' . $point;
        }
        $filledPathPoints .= ' L' . ($viewBoxWidth - $this->padding) . ',' . ($xAxisYPosition);
        $filledPathPoints .= ' L' . ($this->padding) . ',' . ($xAxisYPosition) . ' Z';

        $gradientId = $this->chartId . "_gradient";

        echo
        <<<EOT
        <svg id="{$this->chartId}" width="100%" height="100%" viewBox="0 0 $viewBoxWidth $viewBoxHeight" class="line-chart">
        <defs>
        <linearGradient id="$gradientId" x1="0%" y1="100%" x2="0%" y2="0%">
            <stop offset="10%" style="stop-opacity:0.15;stop-color:{$this->color}" />
            <stop offset="100%" style="stop-opacity:0.8;stop-color:{$this->color}" />
        </linearGradient>
    </defs>
    <path d="$filledPathPoints" fill="url(#$gradientId)" />
        <polyline fill="none" stroke-linecap='round' stroke-width="2" points="$pointsString" stroke="{$this->color}" />
    EOT;

        if ($this->legend) {
            foreach ($points as $index => $point) {
                list($x, $y) = explode(',', $point);
                echo "<line x1='{$x}' y1='{$xAxisYPosition}' x2='{$x}' y2='{$y}' stroke-width='1' stroke-dasharray='5,5' stroke={$this->color} />";
                echo "<line x1='{$yAxisXPosition}' y1='{$y}' x2='{$x}' y2='{$y}' stroke-width='1' stroke-dasharray='5,5' stroke={$this->color} />";
            }
        }

        $index = 0;
        foreach ($this->data as $row) {
            $x = $index * $increment + $this->padding;
            $yLabelPosition = $xAxisYPosition + 15;
            $percentageHeight = $this->calculatePercentage($row[$this->colName2]);
            $y = $innerHeight - ($percentageHeight / 100 * $innerHeight) + $this->padding;
            if ($this->legend) {
                echo "<text font-size='10' x='$x' y='$yLabelPosition' text-anchor='middle'>{$row[$this->colName1]}</text>";
                $yAxisValue = round($row[$this->colName2], 2);
                echo "<text x='" . ($yAxisXPosition - 5) . "' y='$y' font-size='10' text-anchor='end' alignment-baseline='middle'>{$yAxisValue}</text>";
                echo "<circle cx='{$x}' cy='{$y}' r='3' fill={$this->color} />";
            }
            $index++;
        }

        echo <<<EOT
    </svg>
    EOT;

        $yAxisLabels = [0, $this->maxValue / 2, $this->maxValue];
        foreach ($yAxisLabels as $label) {
            $percentageHeight = $this->calculatePercentage($label);
            $y = $viewBoxHeight - ($percentageHeight / 100 * ($viewBoxHeight - 2 * $this->padding));

            if ($y < 0) {
                $y = $this->padding;
            } else if ($y > $viewBoxHeight) {
                $y = $viewBoxHeight - $this->padding;
            }
        }

        echo "</svg>";
    }

    private function calculatePercentage($value): float
    {
        if ($this->maxValue == 0) {
            return 0;
        } else {
            return ($value / $this->maxValue) * 100;
        }
    }

}
