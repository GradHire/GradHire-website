<?php
/** @var $offres \app\src\model\dataObject\Offre */

use app\src\core\components\Modal;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\repository\OffresRepository;


$this->title = 'Offres';

Auth::check_role(Roles::Student, Roles::Manager, Roles::Staff, Roles::Teacher, Roles::Tutor);
if (Auth::has_role(Roles::Staff, Roles::Manager)) {
    $modal = new Modal("Êtes-vous sûr de vouloir archiver cette offre ?", "Oui, archiver", '
 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor"
                 class="text-zinc-400 dark:text-zinc-500 w-11 h-11 mb-3.5 mx-auto">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
            </svg>');
}
?>
<form class="m-0 p-0" method="GET" action="offres">
    <div class="w-full flex flex-col pt-12 pb-24 gap-4">
        <div class="flex flex-row gap-2 w-full">
            <?php
            if (!Auth::has_role(Roles::Student, Roles::Tutor, Roles::Teacher)) {
                echo " <a href=\"/offres/create\" class=\"border-2 border-zinc-200 rounded-lg bg-zinc-50 p-3 px-4 flex justify-center items-center cursor-pointer\">
            <svg class=\"w-5 h-5 text-zinc-500 dark:text-zinc-400\" xmlns=\"http://www.w3.org/2000/svg\" fill=\"none\" viewBox=\"0 0 24 24\" stroke-width=\"1.5\" stroke=\"currentColor\">
                <path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M12 4.5v15m7.5-7.5h-15\" />
            </svg>
        </a>";
            } ?>
            <div class="w-full">
                <label for="default-search"
                       class="text-sm font-medium text-zinc-900 sr-only dark:text-white">Search</label>
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
                           placeholder="Rechercher une offre">
                    <button type="submit"
                            class="text-white absolute right-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-zinc-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-zinc-600 dark:hover:bg-zinc-700 dark:focus:ring-zinc-800">
                        Rechercher
                    </button>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-4">
            <form method="GET" action="offres">
                <div class="rounded-lg p-4 border-2 border-zinc-200">
                    <?php require_once __DIR__ . '/search.php'; ?>
                </div>
            </form>

            <div class="lg:col-span-3 rounded-lg flex flex-col gap-4">
                <div class="flex flex-col gap-1 w-full">
                    <h2 class="font-bold text-lg">Offres validées</h2>
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-2 2xl:grid-cols-3 grid-cols-1 content-start place-items-stretch justify-items-stretch">
                        <?php
                        if ($offres != null) {
                            foreach ($offres as $offre) {
                                if ($offre->getStatut() === "valider") {
                                    if (Auth::has_role(Roles::Manager, Roles::Staff, Roles::Teacher)) {
                                        require __DIR__ . '/offre.php';
                                    } else if (!Auth::has_role(Roles::Manager, Roles::Staff, Roles::Enterprise, Roles::Teacher, Roles::Tutor) && !(new OffresRepository())->checkArchived($offre)) {
                                        if (Application::getUser()->attributes()["annee"] == 3 && $offre->getAnneeVisee() == 2) {
                                            continue;
                                        } else {
                                            require __DIR__ . '/offre.php';
                                        }
                                    } else if (Auth::has_role(Roles::Enterprise, Roles::Tutor) && $offre->getIdutilisateur() == Application::getUser()->id()) {
                                        require __DIR__ . '/offre.php';
                                    }
                                }
                            }
                        } else {
                            require __DIR__ . '/errorOffre.php';
                        }
                        ?>
                    </div>
                </div>
                <?php if (Auth::has_role(Roles::Manager, Roles::Staff)) {
                    echo '<div class="w-full bg-zinc-200 h-[1px] rounded-full"></div>';
                    echo '<div class="flex flex-col gap-1 w-full">';
                    echo '<h2 class="font-bold text-lg">Offres en attente</h2>';
                }
                ?>
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-2 2xl:grid-cols-3 grid-cols-1 content-start place-items-stretch justify-items-stretch">
                    <?php
                    if ($offres != null) {
                        foreach ($offres as $offre) {
                            if ($offre->getStatut() === "en attente" && Auth::has_role(Roles::Manager, Roles::Staff)) {
                                require __DIR__ . '/offre.php';
                            }
                        }
                    }
                    echo "</div>"; ?>
                </div>
                <?php if (Auth::has_role(Roles::Manager, Roles::Staff)) {
                    echo '<div class="w-full bg-zinc-200 h-[1px] rounded-full"></div>';
                    echo '<div class="flex flex-col gap-1 w-full">';
                    echo '<h2 class="font-bold text-lg">Offres Archiver</h2>';
                }
                ?>
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-2 2xl:grid-cols-3 grid-cols-1 content-start place-items-stretch justify-items-stretch">
                    <?php
                    if ($offres != null) {
                        foreach ($offres as $offre) {
                            if ($offre->getStatut() === "archiver" && Auth::has_role(Roles::Manager, Roles::Staff)) {
                                require __DIR__ . '/offre.php';
                            }
                        }
                    }
                    echo "</div>"; ?>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    window.addEventListener('DOMContentLoaded', function () {

        const resetButton = document.getElementById('reset-button');
        resetButton.addEventListener('click', resetFilters);


        const alternanceInput = document.getElementById('alternance');
        alternanceInput.addEventListener('change', updateUrl)

        const stageInput = document.getElementById('stage');
        stageInput.addEventListener('change', updateUrl)

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


    function resetFilters() {
        const alternanceInput = document.getElementById('alternance');
        alternanceInput.checked = false;

        const stageInput = document.getElementById('stage');
        stageInput.checked = false;

        const searchInput = document.getElementById('default-search');
        searchInput.value = "";

        const selectedAnneeVisee = document.querySelector('select[name="anneeVisee"]');
        selectedAnneeVisee.selectedIndex = 0;

        const selectedThematique = Array.from(document.querySelectorAll('input[name="thematique[]"]:checked'));
        selectedThematique.forEach(checkbox => {
            checkbox.checked = false;
        });

        const selectedDuree = document.querySelector('select[name="duree"]');
        selectedDuree.selectedIndex = 0;

        const gratificationMinSlider = document.getElementById("slider-1");
        gratificationMinSlider.value = gratificationMinSlider.min;

        const gratificationMaxSlider = document.getElementById("slider-2");
        gratificationMaxSlider.value = 12;

        const newUrl = window.location.origin + window.location.pathname;
        window.history.pushState(null, document.title, newUrl);

        window.location.reload();
    }

    function updateUrl() {
        const queryString = [];


        const alternanceInput = document.getElementById('alternance');
        if (alternanceInput.checked) {
            queryString.push(`alternance=${alternanceInput.value}`);
        }

        const stageInput = document.getElementById('stage');
        if (stageInput.checked) {
            queryString.push(`stage=${stageInput.value}`);
        }

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
            queryString.push(`thematique[]=${selectedThematique.join('&thematique[]=')}`);
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

        //reload the page sauf si c un update de search
        if (event.target.id !== 'default-search') {
            window.location.reload();
        }
    }

    window.onload = function () {
        slideOne();
        slideTwo();

        // Récupère les paramètres de recherche de l'URL
        const searchParams = new URLSearchParams(window.location.search);

        // Remplit les champs correspondants si leur paramètre est présent dans l'URL
        fillFieldsBasedOnUrlParams(searchParams, 'alternance', 'checkbox');
        fillFieldsBasedOnUrlParams(searchParams, 'stage', 'checkbox');
        fillFieldsBasedOnUrlParams(searchParams, 'anneeVisee', 'select');
        fillFieldsBasedOnUrlParams(searchParams, 'duree', 'select');
        fillFieldsBasedOnUrlParams(searchParams, 'gratificationMin', 'range', 'slider-1');
        fillFieldsBasedOnUrlParams(searchParams, 'gratificationMax', 'range', 'slider-2');

        // Pour les cases à cocher de thématique, nous devrons effectuer un traitement particulier
        const sujet = searchParams.get('sujet');
        console.log(sujet);
        const thematiques = searchParams.getAll('thematique[]');
        console.log(thematiques);

        if (sujet !== "") {
            document.getElementById('default-search').value = sujet;
        }

        thematiques.forEach(thematique => {
            document.querySelectorAll('input[name="thematique[]"]').forEach(checkbox => {
                if (checkbox.value === thematique) {
                    checkbox.checked = true;
                }
            });
        });

        if (searchParams.has('gratificationMin')) {
            const sliderOne = document.getElementById("slider-1");
            sliderOne.value = searchParams.get('gratificationMin');
            slideOne();  // Met à jour l'affichage du slider
        }

        // Vérifie si gratificationMax est dans l'URL et met à jour slider-2 si c'est le cas
        if (searchParams.has('gratificationMax')) {
            const sliderTwo = document.getElementById("slider-2");
            sliderTwo.value = searchParams.get('gratificationMax');
            slideTwo();  // Met à jour l'affichage du slider
        }
    };

    function fillFieldsBasedOnUrlParams(searchParams, param, type, elementId = null) {
        // Si l'élément ID n'est pas passé, on suppose qu'il est le même que le paramètre
        const element = document.getElementById(elementId || param);

        if (searchParams.has(param)) {
            if (type === 'checkbox') {
                element.checked = searchParams.get(param) === 'true';
            } else if (type === 'text' || type === 'select' || type === 'range') {
                element.value = searchParams.get(param);
            }
        }
    }

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
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        const checkbox = document.querySelector('#AcceptConditions');
        let forms = document.querySelectorAll('.formAdminSupprimer');
        let linkTag = document.querySelectorAll('.offreBox');
        let checked = localStorage.getItem('AcceptConditions');

        if (checked !== null) checkbox.checked = (checked === 'true');

        forms.forEach((form) => {
            form.style.display = checkbox.checked ? "flex" : "none";
        });

        linkTag.forEach((link) => {
            if (checkbox.checked) link.classList.add('animate-wiggle');
            else link.classList.remove('animate-wiggle');
        });

        checkbox.addEventListener('change', function () {
            localStorage.setItem('AcceptConditions', this.checked);
            forms = document.querySelectorAll('.formAdminSupprimer');
            forms.forEach((form) => {
                form.style.display = this.checked ? "flex" : "none";
            });

            linkTag.forEach((link) => {
                if (this.checked) link.classList.add('animate-wiggle');
                else link.classList.remove('animate-wiggle');
            });
        });
    });
    let currentModal = null;
</script>



