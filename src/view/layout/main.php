<?php

use app\src\core\components\Lien;
use app\src\core\components\Notification;
use app\src\core\components\Separator;
use app\src\model\Application;

/** @var Lien $lienAccueil
 * @var Lien $lienOffres
 */

require __DIR__ . '/liens.php';

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

<div id="nav-container"
     class="hidden fixed top-0 left-0 w-full h-screen bg-white bg-opacity-90 backdrop-blur-xl backdrop-filter z-50 mt-[65px]">
    <div class="flex flex-col justify-center items-center space-y-8 uppercase mt-[50px]">
        <a href="/"
           class="flex items-center text-xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800">Accueil</a>
        <a href="/dashboard"
           class="flex items-center text-xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800">Dashboard</a>
        <a href="/about"
           class="flex items-center text-xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800">A propos</a>
    </div>
</div>
<div id="blur-background" class="hidden w-screen h-screen fixed z-50 top-0 left-0 backdrop-blur-md"></div>
<div class="w-full flex  justify-start items-start flex-row ">
    <div class="relative text-[14px] w-full md:max-w-[275px] m-0 bg-white isolate justify-around border-r text-[#1A2421] backdrop-blur-xl p-4 [ shadow-black/5 shadow-2xl ] fixed top-0 left-0 z-40 w-64 h-screen ">
        <div class="h-full justify-between items-start w-full flex gap-8 flex-col ">
            <div class="flex flex-row w-full justify-between">
                <a href="/">
                    <span class="sr-only">GradHire</span>
                    <img class="h-7 pl-4 w-auto" src="/resources/images/logo.png" alt="">
                </a>
                <div class="h-7 w-7 justify-center items-center flex hover:-translate-x-0.5 duration-150 cursor-pointer group">
                    <label for="sidebar-icon"></label><input class="absolute w-5 h-5 peer opacity-0 cursor-pointer"
                                                             name="sidebar-icon" id="sidebar-icon" type="checkbox">
                    <svg width="23" height="20" viewBox="0 0 23 20" fill="none" xmlns="http://www.w3.org/2000/svg"
                         class="duration-300 stroke-zinc-500 group-hover:stroke-zinc-800 w-5 h-5">
                        <path d="M19 19L4 19C3.20435 19 2.44129 18.7629 1.87868 18.341C1.31607 17.919 1 17.3467 1 16.75L1 3.25C1 2.65326 1.31607 2.08097 1.87868 1.65901C2.44129 1.23705 3.20435 1 4 1L19 1M19 19C19.7957 19 20.5587 18.7629 21.1213 18.341C21.6839 17.919 22 17.3467 22 16.75L22 3.25C22 2.65326 21.6839 2.08097 21.1213 1.65901C20.5587 1.23705 19.7957 1 19 1M19 19L9 19C8.20435 19 7.44129 18.7629 6.87868 18.341C6.31607 17.919 6 17.3467 6 16.75L6 3.25C6 2.65326 6.31607 2.08097 6.87868 1.65901C7.44129 1.23705 8.20435 1 9 1L19 1"
                              stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path class="sidebar-path-to-spin" d="M15.75 13.5L12 9.75L15.75 6" stroke-width="1.5"
                              stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <style>
                    #sidebar-icon:checked + svg .sidebar-path-to-spin {
                        rotate: 180deg;
                        transform: translate(-28px, -20px);
                    }

                    #sidebar-icon:not(:checked) + svg .sidebar-path-to-spin {
                        rotate: 0deg;
                        transform: translate(0, 0);
                    }
                </style>
            </div>
            <div class="h-full overflow-y-auto flex gap-2 flex-col w-full ">
                <div class="w-full flex flex-row justify-between items-center">
                    <span class="uppercase text-zinc-400 font-semibold">Sections</span>
                    <button>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                             class="w-5 h-5 hover:fill-zinc-800 fill-zinc-400 duration-300">
                            <path d="M10.75 6.75a.75.75 0 00-1.5 0v2.5h-2.5a.75.75 0 000 1.5h2.5v2.5a.75.75 0 001.5 0v-2.5h2.5a.75.75 0 000-1.5h-2.5v-2.5z"/>
                        </svg>
                    </button>
                </div>
                <?php
                $lienAccueil->render();
                $lienOffres->render();
                new Separator([]);
                ?>
                <div class="w-full flex flex-row justify-between items-center">
                    <span class="uppercase text-zinc-400 font-semibold">Actions</span>
                    <button>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                             class="w-5 h-5 hover:fill-zinc-800 fill-zinc-400 duration-300">
                            <path d="M10.75 6.75a.75.75 0 00-1.5 0v2.5h-2.5a.75.75 0 000 1.5h2.5v2.5a.75.75 0 001.5 0v-2.5h2.5a.75.75 0 000-1.5h-2.5v-2.5z"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="flex items-start justify-start flex-col gap-4 w-full">
                <?php new Separator([]); ?>
                <div class="w-full flex flex-row justify-between items-st1">
                    <a class="flex flex-row gap-4 items-center justify-center text-sm font-medium text-zinc-600 hover:text-zinc-800"
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
                    <div class="flex flex-row gap-4 items-center justify-center text-sm font-medium text-zinc-700 hover:text-zinc-800 group relative">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor"
                             class="w-6 h-6 stroke-zinc-600 group-hover:stroke-zinc-800 cursor-pointer">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M6.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM18.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                        </svg>
                        <div class="opacity-0 group-hover:visible invisible group-hover:opacity-100 flex flex-col items-center justify-around duration-300 transition-all transform translate-x-16 -translate-y-6 group-hover:-translate-y-12 absolute top-0 right-0 w-32 h-14 bg-white drop-shadow border rounded-[8px]">
                            <a href="/profile"
                               class="text-sm font-medium text-zinc-600 hover:text-zinc-800 hover:underline">Modifier le
                                profil</a>
                            <a href="/logout"
                               class="text-sm text-zinc-600 hover:text-zinc-800 font-medium hover:underline ">Se
                                déconnecter</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="w-full flex flex-col justify-start items-start p-4 gap-12">
        <?php Notification::show(); ?>
        <div class="w-full border rounded-2xl flex flex-row max-h-[65px] min-h-[65px] bg-white items-center justify-between px-6">
            <h2 class="text-xl font-semibold"><?= $this->title ?></h2>
            <div class="max-w-[55%] h-[30px] w-full border rounded-[8px] bg-zinc-100"></div>
            <button class=" p-[0.8px] duration-300 group relative">
                <span class="w-2.5 h-2.5 bg-blue-400 absolute top-0 right-0 rounded-full animate-ping "></span>
                <span class="w-2.5 h-2.5 bg-blue-400 absolute top-0 right-0 rounded-full border"></span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                     class="w-6 h-6 group-hover:fill-zinc-800 fill-zinc-600">
                    <path fill-rule="evenodd"
                          d="M5.25 9a6.75 6.75 0 0113.5 0v.75c0 2.123.8 4.057 2.118 5.52a.75.75 0 01-.297 1.206c-1.544.57-3.16.99-4.831 1.243a3.75 3.75 0 11-7.48 0 24.585 24.585 0 01-4.831-1.244.75.75 0 01-.298-1.205A8.217 8.217 0 005.25 9.75V9zm4.502 8.9a2.25 2.25 0 104.496 0 25.057 25.057 0 01-4.496 0z"
                          clip-rule="evenodd"/>
                </svg>

            </button>
        </div>
        {{content}}
    </div>
</div>
<script>
    var burgerBtn = document.getElementById("burger-btn");
    var navContainer = document.getElementById("nav-container");

    burgerBtn.addEventListener("click", function () {
        if (navContainer.classList.contains('animate-slide-out') || navContainer.classList.contains('hidden')) {
            navContainer.classList.remove('animate-slide-out', 'hidden');
            navContainer.classList.add('animate-slide-in');
        } else {
            navContainer.classList.remove('animate-slide-in');
            navContainer.classList.add('animate-slide-out');

            // To hide the menu after the animation completes
            setTimeout(function () {
                navContainer.classList.add('hidden');
            }, 500);
        }
    });

</script>
</body>
</html>