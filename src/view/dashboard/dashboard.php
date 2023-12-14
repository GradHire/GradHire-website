<?php

use app\src\core\components\Separator;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\View;
use app\src\view\dashboard\components\dashboard_blocks\HorizontalBarChartBlock;
use app\src\view\dashboard\components\dashboard_blocks\LineChartBlock;
use app\src\view\dashboard\components\dashboard_blocks\NumBlock;
use app\src\view\dashboard\components\dashboard_blocks\PercentageBlock;
use app\src\view\dashboard\components\dashboard_blocks\PieChartBlock;
use app\src\view\dashboard\components\dashboard_blocks\VerticalBarChartBlock;

/** @var array $data
 */

$this->title = 'Dashboard';

View::setCurrentSection('Dashboard');

//import js
echo '<script type="text/javascript" src="/resources/js/animate-counter.js"></script>';
echo '<script type="text/javascript" src="/resources/js/animate-barChartHorizontal.js"></script>';
echo '<script type="text/javascript" src="/resources/js/animate-pieChart.js"></script>';
echo '<script type="text/javascript" src="/resources/js/animate-barChartVertical.js"></script>';
echo '<script type="text/javascript" src="/resources/js/animate-lineChart.js"></script>';

function OffreCard($offre)
{

    $mail = substr($offre['emailentreprise'], 0, 6) . '…' . substr($offre['emailentreprise'], -3);
    $description = substr($offre['description'], 0, 50) . '…';
    $date = date_create($offre['datecreation']);
    $date = date_format($date, 'd/m/Y');

    echo <<<EOT
<div class="h-[125px] w-full border-gray-200 border rounded-[8px] flex flex-col justify-between items-center bg-white relative px-6 py-3 mb-5">
                <div class="w-full flex flex-row items-center justify-between">
                    <a class="font-semibold hover:underline" href="/offres/{$offre['idoffre']}">Offre n°{$offre['idoffre']}</a>
                    <p class="text-zinc-400 font-light text-xs">{$date}</p>
                </div>
                <div class="w-full flex flex-row justify-between items-center text-xs">
                    <div class="grid grid-cols-2 max-w-[200px]">
                    <p class="font-light text-zinc-400">Nom Entreprise : </p>
                                        <a class="hover:underline" href="/entreprises/{$offre['idutilisateur']}">{$offre['nomentreprise']}</a>
                    <p class="font-light text-zinc-400">Sujet : </p>
                                        <p>{$offre['sujet']}</p>

                    <p class="font-light text-zinc-400">Thematique : </p>
                                        <p>{$offre['thematique']}</p>
                    </div>
                    <div class="h-full w-[1px] bg-zinc-300"></div>
                    <div class="grid grid-cols-2 max-w-[200px]">
                    <p class="font-light text-zinc-400">Description : </p>
                                        <p>{$description}</p>
                    <p class="font-light text-zinc-400">Email : </p>
                                        <p><a class="hover:underline" href="mailto:{$offre['emailentreprise']}">{$mail}</a></p>

                    <p class="font-light text-zinc-400">Telephone : </p>
                    <p><a class="hover:underline" href="tel:{$offre['telephoneentreprise']}">{$offre['telephoneentreprise']}</a></p>
                    </div>
                    <div class="h-full w-[1px] bg-zinc-300"></div>
                    <div class="rounded-[8px] border overflow-hidden">
                        <iframe src="https://yandex.com/map-widget/v1/?ll=3.850089%2C43.634623&mode=search&sll=10.854186%2C49.182076&sspn=73.212891%2C44.753627&text={$offre['adresse']}&z=16.97" width="150" height="60"  allowfullscreen></iframe>
                    </div>
                </div>
                <div class="h-[50px] w-full border-zinc-200 border rounded-[8px] bg-zinc-50 absolute bottom-0 scale-[98%] translate-y-[8px] -z-[1]"></div>
                <div class="h-[50px] w-full border-zinc-200 border rounded-[8px] bg-zinc-100  absolute bottom-0 scale-[95%] translate-y-[16px] -z-[2]"></div>
</div>
EOT;
}

?>

<div class="w-full flex md:flex-row flex-col justify-between items-start gap-4 mx-auto ">
    <div class="flex flex-col gap-4 w-full ">
        <div class="relative w-full bg-zinc-50 isolate overflow-hidden border rounded-2xl text-[#1A2421] backdrop-blur-xl [ p-2 md:p-4 ] [ border-[1px] border-solid border-black  border-opacity-10 ] [ shadow-black/5 shadow-2xl ]">
            <div class="flex flex-col gap-4 items-start justify-start">
                <?php
                echo '<div class="w-full gap-2 grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3">';
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
                echo '<div class="w-full gap-2 grid grid-cols-2 max-lg:grid-cols-1">';
                NumBlock::render([
                    'title' => 'Stages',
                    'value' => $data['numBlockData1']['nombreoffresstageactives'] ?? 0,
                ]);
                NumBlock::render([
                    'title' => 'Alternances',
                    'value' => $data['numBlockData1']['nombreoffresalternanceactives'] ?? 0,
                ]);
                echo '<div class="lg:col-span-2">';
                NumBlock::render([
                    'title' => 'Pourvues',
                    'value' => $data['numBlockData1']['nombrepositionspourvues'] ?? 0,
                ]);
                echo '</div></div>';
                echo '<div class="w-full col-span-3">';
                new Separator([]);
                echo '</div>';
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
                echo '<div class="w-full col-span-3">';
                if (Auth::has_role(Roles::Manager, Roles::Staff, Roles::ChefDepartment)) {
                new Separator([]);
                }
                echo '</div>';
                echo '<div class="flex flex-row w-full gap-2">';
                echo '</div>';
                ?>
            </div>
        </div>
        <?php if (Auth::has_role(Roles::Manager, Roles::Staff, Roles::ChefDepartment)) { ?>
            <div class="relative grow isolate overflow-hidden w-full gap-4 flex flex-col bg-zinc-50 border rounded-2xl text-[#1A2421] backdrop-blur-xl [ p-8 md:p-10 lg:p-10 ] [ border-[1px] border-solid border-black  border-opacity-10 ] [ shadow-black/5 shadow-2xl ]">
                <div class='flex flex-col gap-2 items-start justify-start'>
                    <div class="w-full relative">
                        <div class="w-full h-5 absolute top-0 left-0 z-10 bg-gradient-to-b from-zinc-50 to-transparent"></div>
                        <div class="w-full h-5 absolute bottom-0 left-0 z-10 bg-gradient-to-t from-zinc-50 to-transparent"></div>
                        <div class="w-full flex flex-col gap-2 justify-start items-start max-h-[635px] overflow-y-scroll py-3 pr-3 ">
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
                                        echo <<<EOT
<div class="min-w-[18px] min-h-[18px] max-w-[18px] flex justify-center items-center max-h-[18px] border border-zinc-300 bg-white rounded-full">
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"  class="w-3 h-3 stroke-zinc-500">
  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
</svg>

</div>
EOT;

                                        OffreCard($offre);
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
        <?php } ?>
    </div>