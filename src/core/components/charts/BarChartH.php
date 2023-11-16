<?php

namespace app\src\core\components\charts;

/** @var array $statsDistributionDomaine */
class BarChartH
{
    private $data;
    private $maxValue;
    private $chartId;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->chartId = uniqid();
        $columnValues = array_column($data, 'nombreoffres');
        $this->maxValue = $columnValues ? max($columnValues) : 0;
    }

    public function getChartId(): string
    {
        return $this->chartId;
    }

    public function render(): void
    {
        foreach ($this->data as $row) {
            $percentage = $this->calculatePercentage($row['nombreoffres']);
            $domain = !empty($row['thematique']) ? $row['thematique'] : 'Autre';
            echo <<<EOT
<div class="mb-2 rounded-md bg-gradient-to-r from-zinc-200 to-zinc-400 relative overflow-hidden shadow">
    <div class="h-[18px] rounded-md bg-gradient-to-r from-zinc-400 to-zinc-600 animated-bar shadow-md"
         style="width: 0;"
         data-chart-id="{$this->chartId}"
         data-percentage="{$percentage}">
    </div>
    <p class="absolute left-0 top-0 ml-2 text-white drop-shadow-sm uppercase text-[12px]">$domain</p>
    <p class="absolute right-0 top-0 mr-2 text-white drop-shadow-sm uppercase text-[12px]">{$row['nombreoffres']}</p>
</div>
EOT;
            $chartId = $this->chartId;
            echo <<<EOT
        <script>
            function animateBars(chartId) {
                const bars = document.querySelectorAll(`.animated-bar[data-chart-id='{$chartId}']`);
                bars.forEach(bar => {
                    const percentageWidth = bar.getAttribute('data-percentage');
                    bar.style.width = percentageWidth + '%';
                });
            }
        </script>
    EOT;
        }
    }

    private function calculatePercentage($value): float|int
    {
        return ($value / $this->maxValue) * 100;
    }

}



