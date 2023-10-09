<?php
/** @var $offres \app\src\model\dataObject\Offre */

use app\src\model\Auth\Auth;
use app\src\model\Users\Roles;

?>

<form method="GET" action="offres" class="w-full gap-4 flex flex-col">
    <div class="flex flex-row gap-2 w-full">
        <?php
        if (!Auth::has_role(Roles::Student)) {
            echo <<<HTML
        <a href="/offres/create" class="border-2 border-zinc-200 rounded-lg bg-zinc-50 p-3 px-4 flex justify-center items-center cursor-pointer">
            <svg class="w-5 h-5 text-zinc-500 dark:text-zinc-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
        </a>
HTML;
        }
        ?>
        <div class="w-full">
            <label for="default-search" class="text-sm font-medium text-zinc-900 sr-only dark:text-white">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-zinc-500 dark:text-zinc-400" aria-hidden="true"
                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input type="search" id="default-search" name="sujet"
                       class="block w-full p-4 pl-10 text-sm text-zinc-900 border-2 border-zinc-200 rounded-lg bg-zinc-50 focus:ring-zinc-500 focus:border-zinc-500 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500"
                       placeholder="Search Stages, Alternances...">
                <button type="submit"
                        class="text-white absolute right-2.5 bottom-2.5 bg-zinc-700 hover:bg-zinc-800 focus:ring-4 focus:outline-none focus:ring-zinc-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-zinc-600 dark:hover:bg-zinc-700 dark:focus:ring-zinc-800">
                    Search
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-4 ">
        <div class="rounded-lg p-4 border-2 border-zinc-200">
            <?php require_once __DIR__ . '/search.php'; ?>
        </div>
        <div class="lg:col-span-3 rounded-lg">
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 grid-cols-1 content-start place-items-stretch justify-items-stretch">
                <?php
                if ($offres != null) {
                    foreach ($offres as $offre) {
                        if($offre->getStatut() != "en attente")
                            require __DIR__ . '/offre.php';
                    }
                } else {
                    require __DIR__ . '/errorOffre.php';
                }
                echo "</div>"; ?>
            </div>
        </div>
</form>
<script>
    window.addEventListener('DOMContentLoaded', function () {

        const searchInput = document.getElementById('default-search');
        searchInput.addEventListener('keyup', updateUrl);


        const anneeViseeRadios = document.querySelectorAll('select[name="anneeVisee"]');
        anneeViseeRadios.forEach(radio => {
            radio.addEventListener('change', updateUrl);
        });

        const dureeRadios = document.querySelectorAll('select[name="duree"]');
        dureeRadios.forEach(radio => {
            radio.addEventListener('change', updateUrl);
        });

        const thematiqueCheckboxes = document.querySelectorAll('input[name="thematique[]"]');
        thematiqueCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateUrl);
        });

        const gratificationMinSlider = document.getElementById("slider-1");
        const gratificationMaxSlider = document.getElementById("slider-2");
        gratificationMinSlider.addEventListener('change', updateUrl);
        gratificationMaxSlider.addEventListener('change', updateUrl);
    });

    function updateUrl() {
        const queryString = [];

        const searchInput = document.getElementById('default-search');
        if (searchInput.value && searchInput.value !== "") {
            queryString.push(`sujet=${searchInput.value}`);
        }

        const selectedAnneeVisee = document.querySelector('select[name="anneeVisee"]');
        if (selectedAnneeVisee.value && selectedAnneeVisee.value !== "") {
            queryString.push(`anneeVisee=${selectedAnneeVisee.value}`);
        }

        const selectedThematique = Array.from(document.querySelectorAll('input[name="thematique[]"]:checked')).map(checkbox => checkbox.value);
        if (selectedThematique.length > 0) {
            queryString.push(`thematique=${selectedThematique.join('&thematique=')}`);
        }

        const selectedDuree = document.querySelector('select[name="duree"]');
        if (selectedDuree.value && selectedDuree.value !== "") {
            queryString.push(`duree=${selectedDuree.value}`);
        }

        const gratificationMinSlider = document.getElementById("slider-1");
        const gratificationMaxSlider = document.getElementById("slider-2");

        if (gratificationMinSlider.value !== null && gratificationMaxSlider.value !== null) {
            queryString.push(`gratificationMin=${gratificationMinSlider.value}`, `gratificationMax=${gratificationMaxSlider.value}`);
        }

        const newUrl = window.location.origin + window.location.pathname + (queryString.length > 0 ? '?' + queryString.join('&') : '');
        window.history.pushState(null, document.title, newUrl);
    }

    // Rest of your code...


    window.onload = function () {
        slideOne();
        slideTwo();
    };

    let sliderOne = document.getElementById("slider-1");
    let sliderTwo = document.getElementById("slider-2");
    let displayValOne = document.getElementById("range1");
    let displayValTwo = document.getElementById("range2");
    let minGap = 0;
    let sliderTrack = document.querySelector(".slider-track");
    let sliderMaxValue = document.getElementById("slider-1").max;

    function slideOne() {
        if (sliderTwo && parseInt(sliderTwo.value) - parseInt(sliderOne.value) <= minGap) {
            sliderOne.value = parseInt(sliderTwo.value) - minGap;
        }
        if (displayValOne) {
            displayValOne.textContent = sliderOne.value;
        }
        fillColor();
    }

    function slideTwo() {
        if (sliderTwo && parseInt(sliderTwo.value) - parseInt(sliderOne.value) <= minGap) {
            sliderTwo.value = parseInt(sliderOne.value) + minGap;
        }
        if (displayValTwo) {
            displayValTwo.textContent = sliderTwo.value;
        }
        fillColor();
    }

    function fillColor() {
        if (sliderTrack) {
            percent1 = (sliderOne.value - 4.05) / (sliderMaxValue - 4.05) * 100;
            percent2 = (sliderTwo.value - 4.05) / (sliderMaxValue - 4.05) * 100;
            sliderTrack.style.background = `linear-gradient(to right, #dadae5 ${percent1}%, #71717a ${percent1}%, #71717a ${percent2}%, #dadae5 ${percent2}%)`;
        }
    }


</script>
