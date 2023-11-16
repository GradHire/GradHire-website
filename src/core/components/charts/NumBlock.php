<?php

namespace app\src\core\components\charts;

class NumBlock
{
    private $title;
    private $value;

    public function __construct($title, $value)
    {
        $this->title = $title;
        $this->value = (int)$value;
    }

    public function render(): void
    {
        $title = $this->title;
        $value = $this->value;

        echo <<<EOT
<div class="px-4 py-2 min-w-[80px] w-full min-h-[40px] border border-zinc-200 shadow bg-white rounded-[8px] flex flex-col justify-around items-start">
    <p class="text-xs font-medium text-zinc-400">$title</p>
    <p class="text-3xl font-bold text-zinc-800"><span class="counter" data-target="$value">0</span></p>
</div>
EOT;
    }
}
