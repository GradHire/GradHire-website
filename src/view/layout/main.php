<?php

use app\src\core\components\FormModal;
use app\src\core\components\Lien;
use app\src\core\components\Notification;
use app\src\core\components\Separator;
use app\src\model\Application;
use app\src\model\Form\FormModel;

/** @var array $allSections
 * @var array $allActions
 * @var array $parametres
 */

if (!isset($_COOKIE['sidebar_open']) || ($_COOKIE['sidebar_open'] == 'true')) $isOpen = true; else $isOpen = false;

require __DIR__ . "/sidebar.php";

$parametresContent = file_get_contents(__DIR__ . "/data/parametres_default.json");
$parametres = json_decode($parametresContent, true);

$sections = $parametres['sections'] ?? [];
$actions = $parametres['actions'] ?? [];

if (isset($_SESSION['parametres']) && $_SESSION['parametres'] !== null) {
    $parametres = $_SESSION['parametres'];
    $sections = $parametres['sections'] ?? [];
    $actions = $parametres['actions'] ?? [];
}

$filteredSections = [];
$filteredActions = [];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title> GradHire | <?= $this->title ?></title>
	<script type="text/javascript" src="/resources/js/load-functions.js"></script>
	<script type="text/javascript" src="/resources/js/side-menu.js"></script>
	<!--    <script type="text/javascript" src="/resources/js/animate-burger-menu.js"></script>-->
	<link rel="stylesheet" href="/resources/css/input.css">
	<link rel="stylesheet" href="/resources/css/output.css">
	<link rel="icon" type="image/png" sizes="32x32" href="/resources/images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/resources/images/favicon-16x16.png">
	<style>
        @import url('https://fonts.googleapis.com/css2?family=Gabarito:wght@400;500;600;700;800&display=swap');

        nav {
            background-color: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(20px) saturate(160%) contrast(45%) brightness(140%);
            -webkit-backdrop-filter: blur(20px) saturate(160%) contrast(45%) brightness(140%);
        }
	</style>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
	      integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
	      crossorigin="anonymous" referrerpolicy="no-referrer">
</head>
<body class="font-sans">

<!--<div id="nav-container"-->
<!--     class="hidden fixed top-0 left-0 w-full h-screen bg-white bg-opacity-90 backdrop-blur-xl backdrop-filter z-50 mt-[65px]">-->
<!--    <div class="flex flex-col justify-center items-center space-y-8 uppercase mt-[50px]">-->
<!--        <a href="/"-->
<!--           class="flex items-center text-xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800">Accueil</a>-->
<!--        <a href="/dashboard"-->
<!--           class="flex items-center text-xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800">Dashboard</a>-->
<!--        <a href="/about"-->
<!--           class="flex items-center text-xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800">A propos</a>-->
<!--    </div>-->
<!--</div>-->
<div id="blur-background"
     class="hidden w-screen h-screen fixed z-50 top-0 left-0 bg-white/50 backdrop-blur-[4px]"></div>
<?php
/**
 * @throws \app\src\core\exception\ServerErrorException
 */
function generateFormCheckboxes($items, $selectedItems, $defaultKey)
{
    $checkboxes = [];

    foreach ($items as $itemId => $item) {
        $checkbox = FormModel::checkbox("", [$itemId => $item['nom']]);
        $checkboxes[$itemId] = $checkbox;

        if (in_array($itemId, $selectedItems)) {
            $checkbox->default([$itemId]);
        }
    }

    return new FormModal(function () use ($checkboxes, $defaultKey) {
        $form = new FormModel($checkboxes);
        $form->setAction("/modifierParametres/{$defaultKey}?" . Application::getRedirect());
        $form->start();
        echo "<div class='flex flex-col justify-center items-center gap-2 mb-4'>";
        echo "<span class='text-xl font-bold'>Ajouter une {$defaultKey} dans le menu</span>";
        foreach ($checkboxes as $itemId => $checkbox) {
            $form->field($itemId);
        }
        echo "</div>";
        $form->submit("Enregistrer");
        $form->end();
    });
}

$modalAddSection = generateFormCheckboxes($allSections, $sections, "sections");
$modalAddAction = generateFormCheckboxes($allActions, $actions, "actions");

?>


<div class="w-full flex justify-start items-start bg-zinc-50 flex-row duration-300">
    <div id="sidebar-container"
         class="duration-300 ease-out relative text-[14px] w-full <?= $isOpen ? " max-w-[275px] " : " max-w-[75px] " ?> m-0 bg-white justify-around border-r text-[#1A2421] backdrop-blur-xl p-4 [ shadow-black/5 shadow-2xl ] sticky top-0 left-0 z-40 h-screen ">
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
            <div class="h-full w-full z-40 flex gap-1.5 flex-col relative items-start">
                <div class="w-full flex flex-row justify-between items-center">
                    <span class="uppercase text-zinc-400 font-light text-[8px] duration-300 tracking-widest sectionText <?= $isOpen ? " text-[12px] " : " text-[8px] " ?>">Sections</span>
                    <button <?= $modalAddSection ? $modalAddSection->Show() : "" ?>
                            class="flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                             class="w-5 h-5 hover:fill-zinc-800 fill-zinc-400 duration-300 sectionAdd"
                             style="display: <?= $isOpen ? "block" : "none" ?>">
                            <path d="M10.75 6.75a.75.75 0 00-1.5 0v2.5h-2.5a.75.75 0 000 1.5h2.5v2.5a.75.75 0 001.5 0v-2.5h2.5a.75.75 0 000-1.5h-2.5v-2.5z"/>
                        </svg>
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
                new Separator([]);
                ?>
                <div class="w-full flex flex-row justify-between items-center">
                    <span class="uppercase text-zinc-400 font-light text-[8px] duration-300 tracking-widest sectionText <?= $isOpen ? " text-[12px] " : " text-[8px] " ?>">Actions</span>
                    <button <?= $modalAddAction ? $modalAddAction->Show() : "" ?>
                            class="flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                             class="w-5 h-5 hover:fill-zinc-800 fill-zinc-400 duration-300 sectionAdd"
                             style="display: <?= $isOpen ? "block" : "none" ?>">
                            <path d="M10.75 6.75a.75.75 0 00-1.5 0v2.5h-2.5a.75.75 0 000 1.5h2.5v2.5a.75.75 0 001.5 0v-2.5h2.5a.75.75 0 000-1.5h-2.5v-2.5z"/>
                        </svg>
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
            <div class="flex items-start justify-start flex-col gap-4 w-full">
                <?php new Separator([]); ?>
                <div class="flex items-start justify-start flex-col gap-1.5 w-full">
                    <?php Lien::render($allSections['S12']); ?>
                    <?php Lien::render($allSections['S11']); ?>
                </div>
            </div>
        </div>
        <button type="submit" name="sidebar-icon" id="sidebar-button"
                class=" h-full w-4 absolute right-0 group top-0 translate-x-[15px] ">
            <div class="flex h-8 w-6 flex-col items-center absolute  duration-150 right-0 top-1/2 transform -translate-y-1/2 translate-x-1.5">
                <div id="sideButtonFlash1"
                     class=" h-3 w-1 rounded-full duration-150 bg-zinc-400 group-hover:bg-zinc-800 <?= $isOpen ? " group-hover:rotate-[15deg] group-hover:-translate-x-0.5 " : " group-hover:-rotate-[15deg] group-hover:translate-x-0.5 " ?> translate-y-0.5"></div>
                <div id="sideButtonFlash2"
                     class=" h-3 w-1 rounded-full duration-150 bg-zinc-400 group-hover:bg-zinc-800 <?= $isOpen ? " group-hover:-rotate-[15deg] group-hover:-translate-x-0.5 " : " group-hover:rotate-[15deg] group-hover:translate-x-0.5 " ?> -translate-y-0.5"></div>
            </div>
        </button>
    </div>
    <div class="flex-col flex w-full">
        <div class="sticky top-0 z-10 flex items-start justify-between flex-row gap-4 w-full py-2 border-b bg-white pr-4">
            <div class="w-full flex flex-row items-center justify-between h-[40px] px-4">
                <span class="text-lg tracking-widest font-bold uppercase first-letter:text-2xl first-letter:font-extrabold ">
                <?= $this->title ?>
                </span>
			</div>
			<div class="h-[40px] max-w-[40px] w-full bg-white flex relative items-center group justify-center cursor-pointer">
				<div class="w-2 h-2 absolute top-1 right-1 bg-orange-500 rounded-full border"></div>
				<div class="w-2 h-2 absolute top-1 right-1 bg-orange-500 rounded-full animate-ping "></div>
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
				     class="w-6 h-6 fill-zinc-600 group-hover:fill-orange-600 duration-150 ">
					<path fill-rule="evenodd"
					      d="M10 2a6 6 0 00-6 6c0 1.887-.454 3.665-1.257 5.234a.75.75 0 00.515 1.076 32.91 32.91 0 003.256.508 3.5 3.5 0 006.972 0 32.903 32.903 0 003.256-.508.75.75 0 00.515-1.076A11.448 11.448 0 0116 8a6 6 0 00-6-6zM8.05 14.943a33.54 33.54 0 003.9 0 2 2 0 01-3.9 0z"
					      clip-rule="evenodd"/>
				</svg>

			</div>
			<?php new Separator(['orientation' => 'vertical', 'height' => '[40px]']); ?>
			<a class="flex flex-row gap-4 items-center justify-center min-w-fit text-sm font-medium text-zinc-600 hover:text-zinc-800"
			   href="/profile">
				<div class="rounded-full overflow-hidden h-8 w-8 border">
					<img src="<?= Application::getUser()->get_picture() ?>" alt="Photo de profil"
					     class="w-full h-full object-cover rounded-full aspect-square">
				</div>
				<div class="flex flex-col justify-start items-start">
					<span><?= Application::getUser()->full_name() ?></span>
					<span class="whitespace-nowrap rounded-full bg-zinc-100 px-2 text-center flex justify-center items-center text-xs text-zinc-500">
                                    <?= Application::getUser()->role()->value ?>
                                    </span></div>
			</a>
		</div>
		<div class="w-full flex flex-col justify-start items-start p-4 gap-4">
			<?php Notification::show(); ?>
			{{content}}
			<div id="chatbot"
			     style="display: none"
			     class="w-[350px] !fixed bottom-[20px] right-[20px] bg-white shadow-xl rounded-lg border border-zinc-200 p-4 z-20">
				<div class="flex justify-between w-full pb-2">
					<p class="text-zinc-800 font-bold">Gilou bot</p>
					<button class="p-1 rounded bg-zinc-800"
					        onclick="closeChatbot()">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" class="w-4 h-4">
							<path fill-rule="evenodd"
							      d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z"
							      clip-rule="evenodd"/>
						</svg>
					</button>
				</div>
				<div id="chatbot-chat" class="w-full flex flex-col gap-2 h-[400px] overflow-y-auto">
                    <div class="flex w-full mt-2 space-x-3 max-w-xs">
                        <div class="flex flex-col gap-2">
                            <div class="bg-gray-300 p-3 rounded-r-lg rounded-bl-lg">
                                <p class="text-sm">Salut <?= Application::getUser()->full_name()?> ! Comment puis-je vous aider ?</p>
                            </div>
                            <span class="text-xs text-gray-500 leading-none">Gilou</span>
                        </div>
                    </div>
                </div>
				<div class="flex justify-between w-full gap-2 mt-2">
					<label class="w-full">
						<input type="text" placeholder="Message"
                               id="chatbot-input"
						       class="shadow-sm w-full bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light">
					</label>
					<button onclick="sendMessage()" class="bg-blue-700 hover:bg-blue-800 focus:ring-blue-300 rounded-lg px-3">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" class="w-6 h-6">
							<path d="M3.478 2.405a.75.75 0 00-.926.94l2.432 7.905H13.5a.75.75 0 010 1.5H4.984l-2.432 7.905a.75.75 0 00.926.94 60.519 60.519 0 0018.445-8.986.75.75 0 000-1.218A60.517 60.517 0 003.478 2.405z"/>
						</svg>
					</button>
				</div>
			</div>
			<button id="chatbot-button"
			        onclick="openChatbot()"
			        class="fixed bottom-[20px] right-[20px] bg-blue-700 hover:bg-blue-800 focus:ring-blue-300 rounded-lg p-3">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" class="w-6 h-6">
					<path d="M4.913 2.658c2.075-.27 4.19-.408 6.337-.408 2.147 0 4.262.139 6.337.408 1.922.25 3.291 1.861 3.405 3.727a4.403 4.403 0 00-1.032-.211 50.89 50.89 0 00-8.42 0c-2.358.196-4.04 2.19-4.04 4.434v4.286a4.47 4.47 0 002.433 3.984L7.28 21.53A.75.75 0 016 21v-4.03a48.527 48.527 0 01-1.087-.128C2.905 16.58 1.5 14.833 1.5 12.862V6.638c0-1.97 1.405-3.718 3.413-3.979z"/>
					<path d="M15.75 7.5c-1.376 0-2.739.057-4.086.169C10.124 7.797 9 9.103 9 10.609v4.285c0 1.507 1.128 2.814 2.67 2.94 1.243.102 2.5.157 3.768.165l2.782 2.781a.75.75 0 001.28-.53v-2.39l.33-.026c1.542-.125 2.67-1.433 2.67-2.94v-4.286c0-1.505-1.125-2.811-2.664-2.94A49.392 49.392 0 0015.75 7.5z"/>
				</svg>
			</button>
		</div>
	</div>
</div>
<script src="/resources/js/chatbot.js"></script>
</body>
</html>