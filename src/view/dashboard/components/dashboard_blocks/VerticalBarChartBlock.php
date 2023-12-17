<?php

namespace app\src\view\dashboard\components\dashboard_blocks;

class VerticalBarChartBlock
{

    public static function render(array $params): void
    {
        $data = $params['data'] ?? [];
        $row_1 = $params['row_1'] ?? 'Row1';
        $row_2 = $params['row_2'] ?? 'Row2';
        $color = $params['color'] ?? '#8f34eb';
        $width = $params['size'][0] ?? 300;
        $height = $params['size'][1] ?? 280;
        $padding = $params['size'][2] ?? 20;
        $rounding = $params['size'][3] ?? 4;
        $columnValues = array_column($data, $row_1);
        $maxValue = $columnValues ? max($columnValues) : 1;
        $chartId = uniqid('barchart_', true);

        $count = count($data);
        if ($count === 0) {
            echo "<div class=\"w-full h-full flex items-center justify-center\"><span class=\"text-gray-400\">No data</span></div>";
            return;
        } else if ($count === 1) {
            echo "<div class=\"w-full h-full flex items-center justify-center\"><span class=\"text-gray-400\">Not enough data</span></div>";
            return;
        }
        $barWidth = ($width - $padding - 1) / count($data);
        $increment = $height / $maxValue;

        $gradientId = $chartId . "_gradient";
        echo <<<EOT
            <div class="border rounded-[8px] shadow w-full min-w-[300px] max-h-[380px] bg-white p-4 relative flex flex-col gap-4">
        EOT;
        TitleBlock::render(['title' => 'Moyenne', 'subtitle' => 'Des candidatures par offre par domaine']);

        echo "<svg id=\"$chartId\" width=\"100%\" height=\"100%\" viewBox=\"0 0 $width $height\" class=\"svg-bar-chart\">";

        echo <<<EOT
            <defs>
                <linearGradient id="$gradientId" x1="0%" y1="100%" x2="0%" y2="0%">
                    <stop offset="0%" style="stop-opacity:1 ;stop-color:$color" />
                    <stop offset="100%" style="stop-opacity:0.4;stop-color:$color" />
                </linearGradient>
            </defs>
        EOT;

        $labelIncrement = $height / $maxValue;
        for ($i = 0; $i <= $maxValue; $i += $maxValue / 18) {
            $yPos = $height - $padding - $labelIncrement * $i;
            echo "<line x1=\"" . ($padding) . "\" y1=\"$yPos\" x2=\"" . ($width) . "\" y2=\"$yPos\" stroke-width=\"1\" stroke=\"$color\" opacity=\"0.075\" />";
        }

        $xOffset = $padding + $barWidth / 2;
        for ($i = 0; $i < (count($data) * 2); $i++) {
            echo "<line x1=\"$xOffset\" y1=\"0\" x2=\"$xOffset\" y2=\"$height\" stroke-width=\"1\" stroke=\"$color\" opacity=\"0.075\" />";
            $xOffset += $barWidth / 2;
        }

        $xOffset = $padding;

        foreach ($data as $row) {
            $barHeight = $increment * $row[$row_1];
            $yPos = $height - $barHeight + $padding;
            echo <<<EOT
            <rect class="barChartRect" x="$xOffset" y="$yPos" width="$barWidth" stroke="$color" height="$barHeight" fill="url(#$gradientId)" rx="$rounding" ry="$rounding" />
            EOT;
            echo "<line x1=\"" . ($padding) . "\" y1=\"$yPos\" x2=\"" . ($xOffset + $barWidth / 2) . "\" y2=\"$yPos\" stroke-width=\"1\" stroke-dasharray=\"5,5\" stroke=\"$color\" />";
            echo "<circle cx=\"" . ($xOffset + $barWidth / 2) . "\" cy=\"$yPos\" r=\"3\" fill=\"$color\" />";
            $xOffset += $barWidth;
        }

        $labelIncrement = ($height) / $maxValue;
        for ($i = 0; $i <= $maxValue; $i += $maxValue / 6) {
            $yPos = $height + $padding - $labelIncrement * $i;
            echo "<text x=\"" . ($padding / 2 - 1) . "\" y=\"$yPos\" font-size=\"10\" text-anchor=\"middle\">" . round($i, 2) . "</text>";
        }

        $xOffset = $padding + $barWidth / 2;
        $temp1 = $height - $padding + 2;
        $temp2 = $height - 18;
        echo "<rect x=\"" . ($padding - 3) . "\" y=\"$temp2\" width=\"$width\" height=\"$temp2\" fill=\"#fff\" />";
        echo "<line x1=\"$padding\" y1=\"$temp1\" x2=\"" . ($width - 1) . "\" y2=\"$temp1\" stroke-width=\"1\"  stroke=\"$color\" />";

        foreach ($data as $row) {
            $domain = !empty($row[$row_2]) ? strtoupper(substr($row[$row_2], 0, 3) . '…' . substr($row[$row_2], -3)) : 'AUTRE';
            echo "<text x=\"$xOffset\" y=\"" . ($height - 5) . "\" font-size=\"10\" text-anchor=\"middle\" fill=\"#27272a\" filter=\"url(#dropshadow)\">" . $domain . "</text>";
            $xOffset += $barWidth;
        }

        echo "</svg>";
        echo <<<EOT
        </div>
        EOT;

    }

}
