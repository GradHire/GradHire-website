<?php

namespace app\src\view\dashboard\components\dashboard_blocks;

/** @var array $data */
class LineChartBlock
{

    public static function render(array $params): void
    {
        $data = $params['data'] ?? [];
        $row_1 = $params['row_1'] ?? 'Row1';
        $row_2 = $params['row_2'] ?? 'Row2';
        $color = $params['color'] ?? '#8f34eb';
        $legend = $params['legend'] ?? true;
        $width = $params['size'][0] ?? 300;
        $height = $params['size'][1] ?? 150;
        $padding = $params['size'][2] ?? 20;

        $columnValues = array_column($data, $row_1);
        $maxValue = $columnValues ? max($columnValues) : 0;
        $chartId = uniqid('linechart_', true);

        $count = count($data);
        if ($count === 0) {
            echo "<div class=\"w-full h-full flex items-center justify-center\"><span class=\"text-gray-400\">No data</span></div>";
            return;
        } else if ($count === 1) {
            echo "<div class=\"w-full h-full flex items-center justify-center\"><span class=\"text-gray-400\">Not enough data</span></div>";
            return;
        }
        echo <<<EOT
            <div class="border rounded-[8px] shadow w-full  bg-white p-4 relative">
            <button class="w-5 h-5 border hover:scale-105 duration-75 bg-green-500 backdrop-blur-md shadow absolute top-0 right-0 rounded-full translate-x-2 -translate-y-2 flex items-center justify-center"><span class="text-white text-xl">+</span></button>
        EOT;

        $innerWidth = $width - 2 * $padding;
        $innerHeight = $height - 2 * $padding;

        $points = [];
        $count = count($data);
        if ($count <= 1) $increment = $innerWidth;
        else $increment = $innerWidth / (count($data) - 1);
        $index = 0;

        foreach ($data as $row) {
            $percentageHeight = self::calculatePercentage($row[$row_1], $maxValue);
            $x = $index * $increment + $padding;
            $y = $innerHeight - ($percentageHeight / 100 * $innerHeight) + $padding;
            $points[] = "$x,$y";
            $index++;
        }

        $pointsString = implode(' ', $points);

        $xAxisYPosition = $height - $padding;
        $yAxisXPosition = $padding;

        $filledPathPoints = 'M' . ($padding) . ',' . ($xAxisYPosition);

        foreach ($points as $point) $filledPathPoints .= ' L' . $point;
        $filledPathPoints .= ' L' . ($width - $padding) . ',' . ($xAxisYPosition);
        $filledPathPoints .= ' L' . ($padding) . ',' . ($xAxisYPosition) . ' Z';

        $gradientId = $chartId . "_gradient";

        echo <<<EOT
            <svg id="$chartId" width="100%" height="100%" viewBox="0 0 $width $height" class="line-chart translate-y-8 -translate-x-1.5">
            <defs>
            <linearGradient id="$gradientId" x1="0%" y1="100%" x2="0%" y2="0%">
                <stop offset="10%" style="stop-opacity:0.15;stop-color:$color" />
                <stop offset="100%" style="stop-opacity:0.8;stop-color:$color" />
            </linearGradient>
            </defs>
            <path class='drop-shadow' d="$filledPathPoints" fill="url(#$gradientId)" />
            <polyline class='drop-shadow' fill="none" stroke-linecap='round' stroke-width="2" points="$pointsString" stroke="$color" />
        EOT;

        if ($legend) {
            $labelIncrement = ($height - 2 * $padding) / $maxValue - 1;
            for ($i = 0; $i <= $maxValue + 3; $i += $maxValue / 10) {
                $yPos = $height - $padding - $labelIncrement * $i;
                echo "<line class='drop-shadow' x1=\"" . ($padding) . "\" y1=\"$yPos\" x2=\"" . ($width) . "\" y2=\"$yPos\" stroke-width=\"1\" stroke=\"$color\" opacity=\"0.075\" />";
            }

            $xOffset = $padding + $increment;
            for ($i = 0; $i < (count($data)); $i++) {
                echo "<line class='drop-shadow' x1=\"$xOffset\" y1=\"0\" x2=\"$xOffset\" y2=\"$height\" stroke-width=\"1\" stroke=\"$color\" opacity=\"0.075\" />";
                $xOffset += $increment;
            }
        }

        $temp1 = $height - $padding;
        echo "<rect x=\"" . ($padding - 3) . "\" y=\"$temp1\" width=\"$width\" height=\"$padding\" fill=\"#fff\" />";
        echo "<line class='drop-shadow' x1=\"$padding\" y1=\"$temp1\" x2=\"$width\" y2=\"$temp1\" stroke-width=\"1\"  stroke=\"$color\" />";

        if ($legend) {
            foreach ($points as $point) {
                list($x, $y) = explode(',', $point);
                echo "<line class='drop-shadow' x1='$x' y1='$xAxisYPosition' x2='$x' y2='$y' stroke-width='1' stroke-dasharray='5,5' stroke=$color />";
                echo "<line class='drop-shadow' x1='$yAxisXPosition' y1='$y' x2='$x' y2='$y' stroke-width='1' stroke-dasharray='5,5' stroke=$color />";
            }
        }

        $index = 0;
        foreach ($data as $row) {
            $x = $index * $increment + $padding;
            $yLabelPosition = $xAxisYPosition + 15;
            $percentageHeight = self::calculatePercentage($row[$row_1], $maxValue);
            $y = $innerHeight - ($percentageHeight / 100 * $innerHeight) + $padding;
            if ($legend) {
                if ($row_2 === 'mois') {
                    $date = explode('-', $row[$row_2]);
                    $month = $date[1];
                    $year = substr($date[0], 2);
                    echo "<text class='drop-shadow dateLineChart' font-size='10' x='$x' y='$yLabelPosition' text-anchor='middle'>$month/$year</text>";
                } else {
                    echo "<text class='drop-shadow' font-size='10' x='$x' y='$yLabelPosition' text-anchor='middle'>" . $row[$row_2] . "</text>";
                }
                $yAxisValue = round($row[$row_1], 2);
                echo "<text class='drop-shadow' x='" . ($yAxisXPosition - 5) . "' y='$y' font-size='10' text-anchor='end' alignment-baseline='middle'>$yAxisValue</text>";
                echo "<circle class='drop-shadow' cx='$x' cy='$y' r='3' fill=$color />";
            }
            $index++;
        }

        echo <<<EOT
            </svg>
            </div>
        EOT;
    }

    private static function calculatePercentage(mixed $row_1, mixed $maxValue)
    {
        if ($maxValue == 0) return 0;
        else return ($row_1 / $maxValue) * 100;
    }

}
