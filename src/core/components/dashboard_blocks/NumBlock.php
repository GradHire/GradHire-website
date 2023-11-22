<?php

namespace app\src\core\components\dashboard_blocks;

use app\src\core\components\ComponentInterface;

class NumBlock implements ComponentInterface
{
    private string $title;
    private string $value;

    public function __construct(array $params)
    {
        $this->title = $params['title'] ?? 'Title';
        $this->value = (int)($params['value'] ?? 0);
        $this->render();
    }

    public function render(): void
    {
        $title = $this->title;
        $value = $this->value;

        echo <<<EOT
<div class="px-4 py-2 min-w-[80px] w-full min-h-[40px] border border-zinc-200 shadow bg-white rounded-[8px] flex flex-col justify-around items-start relative">
<button class="w-5 h-5 border hover:scale-105 duration-75 bg-green-500 backdrop-blur-md shadow absolute top-0 right-0 rounded-full translate-x-2 -translate-y-2 flex items-center justify-center"><span class="text-white text-xl">+</span></button>
    <p class="text-xs font-medium text-zinc-400">$title</p>
    <p class="text-3xl font-bold text-zinc-800 drop-shadow"><span class="counter" data-target="$value">0</span></p>
</div>
EOT;
    }
}
