<?php

use app\src\core\exception\ServerErrorException;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\View;
use app\src\view\components\dashboard\HorizontalBarChartBlock;
use app\src\view\components\dashboard\LineChartBlock;
use app\src\view\components\dashboard\NumBlock;
use app\src\view\components\dashboard\OfferCard;
use app\src\view\components\dashboard\PercentageBlock;
use app\src\view\components\dashboard\PieChartBlock;
use app\src\view\components\dashboard\VerticalBarChartBlock;
use app\src\view\components\ui\Separator;
use app\src\view\resources\icons\I_File;

/** @var array $data
 */

$this->title = 'Dashboard';

View::setCurrentSection('Dashboard');

const PATH = '/resources/js/';
$scripts = ['animate-counter.js', 'animate-barChartHorizontal.js', 'animate-pieChart.js', 'animate-barChartVertical.js', 'animate-lineChart.js'];
foreach ($scripts as $script) echo '<script src="' . PATH . $script . '"></script>';

?>

<div class="w-full flex md:flex-row flex-col justify-between items-start gap-4 mx-auto ">
    <div class="flex flex-col gap-4 w-full ">
        <div class="relative w-full isolate overflow-hidden rounded-2xl text-[#1A2421] backdrop-blur-xl md:p-1">
            <div class="flex flex-col gap-4 items-start justify-start">
                <?php
                echo '<div class="w-full flex flex-col gap-2 md:gap-4">';
                    echo '<div class="w-full flex flex-col lg:flex-row gap-2">';
                        PercentageBlock::render([
                                'title' => 'Conventions signées',
                                'text' => 'General',
                                'text2' => 'BUT2',
                                'text3' => 'BUT3',
                                'value' => ($data['percentageBlockData1']['pourcentageannuel'] ?? 0),
                                'value2' => ($data['percentageBlockData1']['pourcentageannuel2'] ?? 0),
                                'value3' => ($data['percentageBlockData1']['pourcentageannuel3'] ?? 0),
                            ]);
                            HorizontalBarChartBlock::render([
                                'data' => $data['barChartHorizontalData1'] ?? [],
                                'row_1' => 'nombrecandidatures',
                                'row_2' => 'thematique',
                            ]);
                            echo '<div class="w-full flex flex-col gap-2">';
                            echo '<div class="w-full flex flex-col lg:flex-row gap-2">';
                                NumBlock::render([
                                    'title' => 'Stages',
                                    'value' => $data['numBlockData1']['nombreoffresstageactives'] ?? 0,
                                ]);
                                NumBlock::render([
                                    'title' => 'Alternances',
                                    'value' => $data['numBlockData1']['nombreoffresalternanceactives'] ?? 0,
                                ]);
                                echo '</div><div class="w-full">';
                                    NumBlock::render([
                                        'title' => 'Pourvues',
                                        'value' => $data['numBlockData1']['nombrepositionspourvues'] ?? 0,
                                    ]);
                                echo '
                                </div>
                                </div>
                                </div>';
                    echo '<div class="w-full max-md:hidden">';Separator::render([]);echo '</div><div class="w-full flex flex-col lg:flex-row gap-2">';
                    VerticalBarChartBlock::render([
                        'data' => $data['barChartVerticalData1'] ?? [],
                        'row_1' => 'moyennecandidaturesparoffre',
                        'row_2' => 'thematique',
                    ]);
                    PieChartBlock::render([
                        'data' => $data['pieChartData1'] ?? [],
                        'row_1' => 'nombreoffres',
                        'row_2' => 'thematique',
                    ]);
                    LineChartBlock::render([
                        'data' => $data['lineChartData1'] ?? [],
                        'row_1' => 'nombrecandidatures',
                        'row_2' => 'mois'
                    ]);
                    echo '</div><div class="w-full max-md:hidden">';
                        try {
                            if (Auth::has_role(Roles::Manager, Roles::Staff, Roles::ChefDepartment)) Separator::render([]);
                        } catch (ServerErrorException $e) {
                            echo $e->getMessage();
                        }
                    echo '</div>';
                    echo '<div class="flex flex-row w-full gap-2">';
                    echo '</div>';
                    ?>
                </div>
            </div>
        <?php try {
            if (Auth::has_role(Roles::Manager, Roles::Staff, Roles::ChefDepartment)) { ?>
                <div class="relative grow isolate overflow-hidden w-full gap-4 flex flex-col text-[#1A2421] bg-white p-8 border rounded-[8px] shadow max-lg:hidden">
                    <div class='flex flex-col gap-2 items-start justify-start'>
                        <div class="w-full relative">
                            <div class="w-full h-5 absolute top-0 left-0 z-10 bg-gradient-to-b from-white to-transparent"></div>
                            <div class="w-full h-5 absolute bottom-0 left-0 z-10 bg-gradient-to-t from-white to-transparent"></div>
                            <div class="w-full flex flex-col gap-2 justify-start items-start max-h-[635px] overflow-y-scroll example py-3 pr-3 ">
                                <div class="flex flex-row justify-start items-start w-full h-full">
                                    <div class="w-full flex flex-col justify-start items-start">
                                        <?php
                                        $counter = 0;
                                        if ($data['lastActionsData1'] === []
                                        ) echo "<div class=\"w-full h-full flex items-center justify-center\"><span class=\"text-gray-400\">Il n'y a pas d'offres de cette semaine</span></div>";
                                        foreach ($data['lastActionsData1'] as $offre) {
                                            echo <<<EOT
                                                <div class="flex flex-row justify-between items-center gap-2 w-full relative">
                                            EOT;
                                            if ($counter != 0) {
                                                echo <<<EOT
                                                    <span class="w-[1px] bg-zinc-300 h-[150px] mr-2"></span>
                                                    <span class="w-6 h-6 border-zinc-300 border-b border-l rounded-bl-2xl absolute -translate-y-3 -z-[1]"></span>
                                                EOT;
                                            } else {
                                                echo <<<EOT
                                                    <span class="w-[1px] bg-zinc-300 h-[75px] translate-y-14 mr-2"></span>
                                                    <span class="w-6 h-6 border-zinc-300 border-t border-l rounded-tl-2xl absolute translate-y-3 -z-[1]"></span>
                                                EOT;
                                            }

                                            $counter += 1;
                                            echo "<div class=\"min-w-[18px] min-h-[18px] max-w-[18px] flex justify-center items-center max-h-[18px] border border-zinc-300 bg-white rounded-full\">";
                                            echo I_File::render('w-3 h-3 stroke-zinc-500');
                                            echo "</div>";

                                            OfferCard::render([
                                                'offer' => $offre,
                                            ]);
                                            echo <<<EOT
                                                </div>
                                            EOT;
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
        } catch (ServerErrorException $e) {
            echo $e->getMessage();
        } ?>
    </div>
</div>
