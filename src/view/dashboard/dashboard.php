<?php

use app\src\core\components\dashboard_blocks\HorizontalBarChartBlock;
use app\src\core\components\dashboard_blocks\NumBlock;
use app\src\core\components\dashboard_blocks\PercentageBlock;
use app\src\core\components\dashboard_blocks\PieChartBlock;
use app\src\core\components\dashboard_blocks\VerticalBarChartBlock;

/** @var array $data
 * @var string $currentTab
 */

if ($currentTab === 'tab1') $this->title = 'Statistiques';
elseif ($currentTab === 'tab2') $this->title = 'Actions';
elseif ($currentTab === 'tab3') $this->title = 'Favoris';

//echo '<pre>';
//print_r($data);
//echo '</pre>';

//import js
echo '<script type="text/javascript" src="/resources/js/animate-counter.js"></script>';
echo '<script type="text/javascript" src="/resources/js/animate-barChartHorizontal.js"></script>';
echo '<script type="text/javascript" src="/resources/js/animate-pieChart.js"></script>';
echo '<script type="text/javascript" src="/resources/js/animate-barChartVertical.js"></script>';

?>

<div class="w-full flex md:flex-row flex-col justify-between items-start gap-4 mx-auto pt-12 pb-24">
    <div class="flex flex-col gap-4 w-full md:max-w-[400px]">
        <?php require __DIR__ . '/tabs.php'; ?>
        <div class="relative w-full bg-zinc-50 isolate overflow-hidden border rounded-2xl text-[#1A2421] backdrop-blur-xl [ p-2 md:p-4 ] [ border-[1px] border-solid border-black  border-opacity-10 ] [ shadow-black/5 shadow-2xl ]">
            <?php if ($currentTab === 'tab1') { ?>
            <div class="flex flex-col gap-4 items-start justify-start">
                <?php
                new PercentageBlock([
                    'title' => 'Conventions signées',
                    'text' => 'General',
                    'text2' => 'BUT2',
                    'text3' => 'BUT3',
                    'value' => ($data['percentageBlockData1']['pourcentageannuel'] ?? 0),
                    'value2' => ($data['percentageBlockData1']['pourcentageannuel2'] ?? 0),
                    'value3' => ($data['percentageBlockData1']['pourcentageannuel3'] ?? 0),
                ]);
                echo '<div class="flex flex-row w-full gap-2">';
                new NumBlock([
                    'title' => 'Stages',
                    'value' => $data['numBlockData1']['nombreoffresstageactives'] ?? 0,
                ]);
                new NumBlock([
                    'title' => 'Alternances',
                    'value' => $data['numBlockData1']['nombreoffresalternanceactives'] ?? 0,
                ]);
                echo '</div>';
                new NumBlock([
                    'title' => 'Pourvues',
                    'value' => $data['numBlockData1']['nombrepositionspourvues'] ?? 0,
                ]);
                new HorizontalBarChartBlock([
                    'data' => $data['barChartHorizontalData1'] ?? [],
                    'row_1' => 'nombrecandidatures',
                    'row_2' => 'thematique',
                ]);
                new PieChartBlock([
                    'data' => $data['pieChartData1'] ?? [],
                    'row_1' => 'nombreoffres',
                    'row_2' => 'thematique',
                ]);
                new VerticalBarChartBlock([
                    'data' => $data['barChartVerticalData1'] ?? [],
                    'row_1' => 'moyennecandidaturesparoffre',
                    'row_2' => 'thematique',
                    'size' => [300, 150, 20, 4],
                    'color' => '#8f34eb',
                ]);
                ?>
                <?php
                echo '</div>';
                } elseif ($currentTab === 'tab2') { ?>
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