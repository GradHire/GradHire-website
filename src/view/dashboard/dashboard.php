<?php

use app\src\core\components\Lien;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;

$this->title = 'Dashboard';

/** @var array $data
 * @var string $currentTab
 */

//echo '<pre>';
//print_r($data);
//echo '</pre>';



?>
<div class="w-full flex md:flex-row flex-col justify-between items-start gap-4 mx-auto pt-12 pb-24">
    <div class="flex flex-col gap-4 w-full md:max-w-[400px]">
        <?php require __DIR__ . '/tabs.php'; ?>
        <div class="relative w-full bg-zinc-50 isolate overflow-hidden border rounded-2xl text-[#1A2421] backdrop-blur-xl [ p-2 md:p-4 ] [ border-[1px] border-solid border-black  border-opacity-10 ] [ shadow-black/5 shadow-2xl ]">
            <?php if ($currentTab === 'tab1') { ?>
                <div class="flex flex-col gap-2 items-start justify-start">

                </div>
            <?php } elseif ($currentTab === 'tab2') { ?>
                <div class="flex flex-col gap-2 items-start justify-start">
                    <div class="flex flex-col w-full gap-2 items-center justify-center">
                        <?php require __DIR__ . '/actions.php'; ?>
                    </div>
                </div>
            <?php } elseif ($currentTab === 'tab3') { ?>
                <div class="flex flex-col gap-2 items-start justify-start">

                </div>
            <?php } ?>
        </div>
    </div>
    <div class="relative grow isolate overflow-hidden w-full gap-4 flex flex-col bg-zinc-50 border rounded-2xl text-[#1A2421] backdrop-blur-xl [ p-8 md:p-10 lg:p-10 ] [ border-[1px] border-solid border-black  border-opacity-10 ] [ shadow-black/5 shadow-2xl ]">
        <?php if ($currentTab === 'tab1') { ?>
            <div class='flex flex-col gap-2 items-start justify-start'>

            </div>
        <?php } elseif ($currentTab === 'tab2') { ?>
            <div class='flex flex-col gap-2 items-start justify-start'>

            </div>
        <?php } elseif ($currentTab === 'tab3') { ?>
            <div class='flex flex-col gap-2 items-start justify-start'>

            </div>
        <?php } ?>
    </div>
</div>