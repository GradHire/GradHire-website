<?php

use app\src\model\Application;
use app\src\view\components\layout\Chatbot;
use app\src\view\components\layout\Lien;
use app\src\view\components\ui\FormModal;
use app\src\view\components\ui\Notification;
use app\src\view\components\ui\Separator;
use app\src\view\resources\icons\I_More;
use app\src\view\resources\icons\I_Notification;

/** @var array $allSections
 * @var array $allActions
 * @var array $parametres
 * @var boolean $isOpen
 * @var FormModal $modalAddSection
 * @var FormModal $modalAddAction
 * @var array $filteredSections
 * @var array $filteredActions
 * @var array $sections
 * @var array $actions
 */

$isOpen = isset($_COOKIE['sidebar_open']) && $_COOKIE['sidebar_open'] == 'true';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> GradHire | <?= $this->title ?></title>
    <link rel="stylesheet" href="/resources/css/input.css">
    <link rel="stylesheet" href="/resources/css/output.css">
    <link rel="icon" type="image/png" sizes="32x32" href="/resources/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/resources/images/favicon-16x16.png">
</head>
<body class="font-sans">
<div id="blur-background"
     class="hidden w-screen h-screen fixed z-50 top-0 left-0 bg-white/50 backdrop-blur-[4px]"></div>
<?php require_once __DIR__ . '/parametres/modal-loader.php'; ?>
<div class="w-full flex justify-start items-start flex-row duration-300 max-h-screen overflow-y-hidden">
    <div id="sidebar-container"
         class="duration-300 ease-out relative text-[14px] w-full <?= $isOpen ? " max-w-[275px] " : " max-w-[75px] " ?> m-0 bg-white justify-around text-[#1A2421]  p-4 sticky top-0 left-0 z-40 h-screen ">
        <div class="h-full justify-between items-start w-full flex gap-8 flex-col relative ">
            <div class="flex flex-row w-full items-center justify-center ">
                <a class="w-full " href="/">
                    <span class="sr-only">GradHire</span>
                    <svg id="logo-big" class="h-7 w-auto mr-auto fill-zinc-800 hover:fill-blue-600 duration-200"
                         style="display: <?= $isOpen ? "block" : "none" ?>"
                         width="532" height="159" viewBox="0 0 532 159" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M75.6273 71.2H74.2273L22.8273 158.2L14.4273 158C8.02734 158 4.02734 156.4 2.42734 153C0.827344 149.8 0.827344 144.8 2.82734 138.4L40.0273 16.4C41.4273 11.2 44.0273 7.2 47.8273 4.4C51.4273 1.59999 55.8273 0.199993 61.0273 0.199993H104.227L96.4273 25.4C95.6273 27.4 94.4273 28.2 92.8273 27.4L66.0273 15.4C63.2273 14.8 61.0273 15.2 59.0273 16.6C57.0273 18.2 55.6273 20.2 54.8273 23C53.8273 26.2 52.4273 30.4 50.8273 35.8C49.2273 41.2 47.4273 47.2 45.4273 53.8C43.4273 60.4 41.2273 67.2 39.0273 74.2C36.8273 81.4 34.8273 88 32.8273 94.2C30.8273 100.6 29.2273 106.2 27.8273 111C26.2273 115.8 25.2273 119.4 24.6273 121.4C23.8273 124.2 23.8273 126 24.6273 127.2C25.4273 128.4 27.4273 128.8 30.6273 128.8L62.8273 71.2H44.6273C44.6273 71.2 44.8273 70.2 45.4273 68.6C45.8273 67 46.4273 65.2 47.0273 63.2C47.6273 61.2 48.2273 59.4 48.8273 57.8C49.2273 56.2 49.6273 55.4 49.6273 55.2C50.0273 53.2 51.2273 52.2 53.4273 52.2H89.0273L56.6273 158H40.4273C38.4273 158 37.8273 157 38.8273 155L75.6273 71.2ZM113.763 67L120.763 66.4L143.763 31.6L144.963 27.4C146.163 23 146.363 19.8 145.163 18C143.963 16.2 142.363 15.2 140.363 15.2H129.563L113.763 67ZM114.563 0.199993H150.163C156.563 0.199993 161.163 2.6 164.163 7.4C166.963 12.2 167.163 18.6 164.763 27L163.163 31.6L136.363 71.2C135.163 73.2 133.763 74.8 132.163 76C130.363 77.2 128.563 77.8 126.763 77.8L110.163 78.2L109.763 80L127.763 82.2C130.963 82.6 133.563 83.8 135.563 85.4C137.563 87 138.963 89.2 139.963 91.6C140.763 94 141.163 96.8 141.163 99.8C140.963 102.8 140.363 105.8 139.563 108.8L127.163 149C126.563 150.8 126.163 152.6 126.163 154.2C125.963 155.8 126.163 157.2 126.963 158H110.163C108.563 158 107.563 157.6 106.963 156.4C106.363 155.4 106.563 153 107.763 149.4L121.363 104.4C122.363 101.2 122.363 98.4 121.363 96C120.163 93.6 118.163 92.4 115.163 92L106.363 91L85.7633 158H66.1633L114.563 0.199993ZM165.365 111.2H182.565L203.565 16.8H202.165L165.365 111.2ZM192.765 0.199993H223.165L189.365 158H171.965L178.965 126.2H159.765L147.565 158H129.965L192.765 0.199993ZM271.665 0.199993C291.665 0.399991 298.865 9.39999 293.665 27.4L282.465 63.8L236.865 158H195.465L243.665 0.199993H271.665ZM218.865 143.2H226.665L263.265 62.6L273.865 27.4C275.265 23 275.265 19.8 274.065 17.8C272.865 15.8 270.265 14.8 266.465 14.8H258.665L218.865 143.2ZM311.634 0.199993H331.234L310.234 68.6H329.234L350.234 0.199993H369.834L321.634 158H302.034L324.834 83.6H305.634L283.034 158H263.434L311.634 0.199993ZM381.993 0.199993H401.593L353.393 158H333.793L381.993 0.199993ZM412.787 67L419.787 66.4L442.787 31.6L443.987 27.4C445.187 23 445.387 19.8 444.187 18C442.987 16.2 441.387 15.2 439.387 15.2H428.587L412.787 67ZM413.587 0.199993H449.187C455.587 0.199993 460.187 2.6 463.187 7.4C465.987 12.2 466.187 18.6 463.787 27L462.187 31.6L435.387 71.2C434.187 73.2 432.787 74.8 431.187 76C429.387 77.2 427.587 77.8 425.787 77.8L409.187 78.2L408.787 80L426.787 82.2C429.987 82.6 432.587 83.8 434.587 85.4C436.587 87 437.987 89.2 438.987 91.6C439.787 94 440.187 96.8 440.187 99.8C439.987 102.8 439.387 105.8 438.587 108.8L426.187 149C425.587 150.8 425.187 152.6 425.187 154.2C424.987 155.8 425.187 157.2 425.987 158H409.187C407.587 158 406.587 157.6 405.987 156.4C405.387 155.4 405.587 153 406.787 149.4L420.387 104.4C421.387 101.2 421.387 98.4 420.387 96C419.187 93.6 417.187 92.4 414.187 92L405.387 91L384.787 158H365.187L413.587 0.199993ZM481.946 0.199993H531.546L526.946 15.2H496.946L480.346 69H505.546L500.946 84H475.746L457.746 143H487.746L483.346 158H433.746L481.946 0.199993Z"/>
                    </svg>
                    <svg id="logo-small" class="h-7 w-auto mx-auto fill-zinc-800 hover:fill-blue-600 duration-200"
                         style="display: <?= $isOpen ? "none" : "block" ?>"
                         width="156" height="238" viewBox="0 0 156 238" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M112.898 106.8H110.798L33.6981 237.3L21.098 237C11.498 237 5.49805 234.6 3.09805 229.5C0.698048 224.7 0.698048 217.2 3.69805 207.6L59.498 24.6C61.598 16.8 65.4981 10.8 71.1981 6.6C76.5981 2.39998 83.198 0.29999 90.9981 0.29999H155.798L144.098 38.1C142.898 41.1 141.098 42.3 138.698 41.1L98.498 23.1C94.298 22.2 90.9981 22.8 87.998 24.9C84.9981 27.3 82.8981 30.3 81.6981 34.5C80.1981 39.3 78.0981 45.6 75.698 53.7C73.2981 61.8 70.598 70.8 67.5981 80.7C64.598 90.6 61.298 100.8 57.9981 111.3C54.698 122.1 51.6981 132 48.6981 141.3C45.698 150.9 43.298 159.3 41.198 166.5C38.798 173.7 37.298 179.1 36.398 182.1C35.198 186.3 35.198 189 36.398 190.8C37.5981 192.6 40.598 193.2 45.398 193.2L93.698 106.8H66.3981C66.3981 106.8 66.698 105.3 67.5981 102.9C68.1981 100.5 69.0981 97.8 69.998 94.8C70.8981 91.8 71.7981 89.1 72.698 86.7C73.2981 84.3 73.8981 83.1 73.8981 82.8C74.4981 79.8 76.298 78.3 79.5981 78.3H132.998L84.3981 237H60.0981C57.098 237 56.1981 235.5 57.6981 232.5L112.898 106.8Z"/>
                    </svg>
                </a>
            </div>
            <div class="h-full w-full">
                <div class="h-full w-full z-40 flex gap-1.5 flex-col relative items-start max-md:max-h-[500px] max-h-[550px] overflow-y-auto example">
                    <div class="w-full flex flex-row justify-between items-center">
                        <span class="uppercase text-zinc-400 font-light text-[8px] duration-300 tracking-widest sectionText <?= $isOpen ? " text-[12px] " : " text-[8px] " ?>">Sections</span>
                        <button <?= $modalAddSection ? $modalAddSection->Show() : "" ?>
                                class="flex items-center justify-center">
                        <span class="sectionAdd" style="display: <?= $isOpen ? "block" : "none" ?>">
                        <?= I_More::render('w-5 h-5 hover:fill-zinc-800 fill-zinc-400 duration-300 ') ?>
                        </span>
                        </button>
                    </div>
                    <?php
                    foreach ($sections as $sectionId) {
                        if (isset($allSections[$sectionId])) {
                            $section = $allSections[$sectionId];
                            $filteredSections[$sectionId] = $section;
                            if ($section['href'] === 'logout') continue;
                            Lien::render($section);
                        }
                    }
                    Separator::render([]);
                    ?>
                    <div class="w-full flex flex-row justify-between items-center">
                        <span class="uppercase text-zinc-400 font-light text-[8px] duration-300 tracking-widest sectionText <?= $isOpen ? " text-[12px] " : " text-[8px] " ?>">Actions</span>
                        <button <?= $modalAddAction ? $modalAddAction->Show() : "" ?>
                                class="flex items-center justify-center">
                        <span class="sectionAdd" style="display: <?= $isOpen ? "block" : "none" ?>">
                        <?= I_More::render('w-5 h-5 hover:fill-zinc-800 fill-zinc-400 duration-300 ') ?>
                        </span>
                        </button>
                    </div>
                    <?php
                    foreach ($actions as $actionId) {
                        if (isset($allActions[$actionId])) {
                            $action = $allActions[$actionId];
                            $filteredActions[$actionId] = $action;
                            Lien::render($action);
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="flex items-start justify-start flex-col gap-4 w-full">
                <?php Separator::render([]) ?>
                <div class="flex items-start justify-start flex-col gap-1.5 w-full">
                    <?php Lien::render($allSections['S12']); ?>
                    <?php Lien::render($allSections['S11']); ?>
                </div>
            </div>
        </div>
        <button type="submit" name="sidebar-icon" id="sidebar-button"
                class="h-full w-4 absolute right-0 group top-0 translate-x-[15px]">
    <span class="flex h-8 w-6 flex-col items-center absolute duration-150 right-0 top-1/2 transform -translate-y-1/2 translate-x-1.5">
        <span id="sideButtonFlash1"
              class="h-3 w-1 rounded-full duration-150 bg-zinc-400 group-hover:bg-zinc-800 <?= $isOpen ? "group-hover:rotate-[15deg] group-hover:-translate-x-0.5" : "group-hover:-rotate-[15deg] group-hover:translate-x-0.5" ?> translate-y-0.5"></span>
        <span id="sideButtonFlash2"
              class="h-3 w-1 rounded-full duration-150 bg-zinc-400 group-hover:bg-zinc-800 <?= $isOpen ? "group-hover:-rotate-[15deg] group-hover:-translate-x-0.5" : "group-hover:rotate-[15deg] group-hover:translate-x-0.5" ?> -translate-y-0.5"></span>
      </span>
        </button>
    </div>
    <div id="centerContainer"
         class="flex-col flex w-full  <?= $isOpen ? "max-w-[calc(100%-275px)]" : "max-w-[calc(100%-75px)]" ?> max-h-screen bg-white">
        <div class="sticky top-0 z-10 flex items-start justify-between flex-row gap-4 w-full py-2 bg-white pr-4 md:pr-8">
            <div class="w-full flex flex-row items-center justify-between h-[40px] px-4">
                <span class="md:text-lg text-[14px] tracking-widest font-bold uppercase md:first-letter:text-2xl first-letter:text-lg leading-3 first-letter:font-extrabold max-md:max-w-[100px]">
                <?= $this->title ?>
                </span>
            </div>
            <div class="h-[40px] max-w-[40px] w-full bg-white flex relative items-center group justify-center cursor-pointer">
                <div class="w-2 h-2 absolute top-1 right-1 bg-blue-500 rounded-full border"></div>
                <div class="w-2 h-2 absolute top-1 right-1 bg-blue-500 rounded-full animate-ping "></div>
                <a href="/notifications">
                    <?= I_Notification::render('md:w-6 md:h-6 w-5 h-5 fill-zinc-500 group-hover:fill-blue-600 duration-150 '); ?>
                </a>
            </div>
            <?php Separator::render(['orientation' => 'vertical', 'height' => '[40px]']) ?>
            <div class="flex items-center justify-center h-[40px] ">
                <a class="flex flex-row gap-4 items-center justify-center min-w-fit text-sm font-medium text-zinc-600 hover:text-zinc-800"
                   href="/profile">
                    <div class="rounded-full overflow-hidden min-h-[30px] min-w-[30px] max-h-[30px] max-w-[30px] md:min-h-[35px] md:min-w-[35px] md:max-h-[35px] md:max-w-[35px] border">
                        <img src="<?= Application::getUser()->get_picture() ?>" alt="Photo de profil"
                             class="w-full h-full object-cover rounded-full aspect-square">
                    </div>
                    <div class="flex flex-col justify-start items-start max-md:hidden w-full">
                        <span class="text-nowrap"><?= Application::getUser()->full_name() ?></span>
                        <span class="whitespace-nowrap rounded-full bg-zinc-100 px-2 text-center flex justify-center items-center text-xs text-zinc-500">
                                    <?= Application::getUser()->role()->value ?>
                                    </span></div>
                </a>
            </div>
        </div>
        <div id="contentToHide"
             class=" w-[calc(100%-16px)] mr-4 flex flex-col justify-start items-start p-4 rounded-3xl gap-4 bg-zinc-50 border h-screen mb-4 overflow-auto example duration-300 ease-out <?= $isOpen ? "max-sm:opacity-0 max-sm:invisible" : "max-sm:opacity-100 max-sm:visible" ?>">
            <?php Notification::show(); ?>
            {{content}}
            <?php
            Chatbot::render([]);
            echo <<<HTML
            <div id="cookie" class="fixed bottom-[20px] right-[20px] w-full bg-white max-w-[calc(100%-140px)] md:max-w-[300px] border rounded-3xl p-3 flex flex-col gap-2 drop-shadow"> 
                  <p class="text-center text-zinc-800 text-[14px]">Ce site utilise des cookies pour améliorer votre expérience utilisateur.</p>
                  <div class="flex flex-row gap-2 items-center justify-center">
                  <button id="bouton-refuser" class="w-full bg-blue-500 focus:ring-red-800 rounded-lg px-3 py-1 text-[14px] text-white">Refuser</button>
                  <button id="bouton-accepter" class="w-full bg-blue-500 focus:ring-green-800 rounded-lg px-3 py-1 text-[14px] text-white">Accepter</button>
                  </div>
                  <p class="text-center text-zinc-500 text-[10px] leading-3">En cliquant sur "Accepter", vous acceptez l'utilisation de cookies, les <a class="text-blue-500 underline" target="_blank" href="/conditionutilisation">conditions d'utilisation</a> et la <a class="text-blue-500 underline" target="_blank" href="/politiqueconfidentialite">politique de confidentialité</a>.</p>
             </div>
            HTML;
            ?>
        </div>
    </div>
</div>
<script src="/resources/js/theme.js"></script>
<script src="/resources/js/cookie.js"></script>
<script src="/resources/js/side-menu.js"></script>
<script src="/resources/js/chatbot.js"></script>
<script src="/resources/js/table.js"></script>
<script src="/resources/js/modal.js"></script>
</body>
</html>
