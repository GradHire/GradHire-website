<?php

namespace app\src\view\dashboard\components\dashboard_blocks;

use app\src\core\components\ComponentInterface;
use app\src\core\components\Separator;

class PercentageBlock
{

    public static function render(array $params): void
    {

        $title = $params['title'] ?? 'Title';
        $text = $params['text'] ?? 'Text';
        $text2 = $params['text2'] ?? 'Text2';
        $text3 = $params['text3'] ?? 'Text3';
        $value = (int)$params['value'] ?? 0;
        $value2 = (int)$params['value2'] ?? 0;
        $value3 = (int)$params['value3'] ?? 0;
        $color1 = $params['color1'] ?? '#ffab61';
        $color2 = $params['color2'] ?? '#ff773d';


        echo <<<EOT

<div class="w-full min-w-[300px] max-h-[150px] border rounded-[8px] p-4 flex flex-col items-center justify-start shadow relative " style="background: linear-gradient(90deg, $color1 0%, $color2 100%);">
    <div class="flex flex-row gap-2 justify-center items-center w-full mb-2">
        <div class="w-[40px] h-[40px] min-h-[40px] min-w-[40px] max-h-[40px] shadow max-w-[40px] rounded-[4px] bg-white border gap-[3px] flex flex-row justify-center items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="$color2" stroke-width="1.5" class="w-6 h-6 drop-shadow">
              <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" />
            </svg>

        </div>
        <div class="w-full">
            <p class="text-xs font-medium text-zinc-600 w-full text-left leading-3">$text</p>
            <p class="text-lg font-bold text-zinc-700 w-full text-left drop-shadow">$title</p>
        </div>
        
    </div>
<div class="px-4 py-2 min-w-[80px] w-full min-h-[40px] border border-zinc-200 bg-white rounded-[4px] flex flex-row items-center justify-around">
    <div class="flex flex-row justify-center items-center w-[30%] ">
    <p class="text-5xl font-bold text-zinc-800 drop-shadow"><span class="counter" data-target="$value">0</span></p>
    <p class="text-2xl font-medium text-zinc-800 -translate-y-2">%</p>
   
    </div>
EOT;
        new Separator(['orientation' => 'vertical', 'height' => '[40px]']);
echo <<<EOT
    <div class="grid grid-cols-2 gap-2 content-center w-[50%] px-6">
    <p class="text-xs font-medium text-zinc-400 place-self-start self-center">$text2</p>
    <p class="text-md font-medium text-zinc-800 place-self-end self-center flex flex-row">
    <span class="counter" data-target="$value2">0</span><span>%</span></p>
    <p class="text-xs font-medium text-zinc-400 place-self-start self-center">$text3</p>
    <p class="text-md font-medium text-zinc-800 place-self-end self-center flex flex-row"><span class="counter" data-target="$value3">0</span><span>%</span></p>
    </div>
</div>
</div>
EOT;
    }
}
