<?php
/** @var $offres Offre */

/** @var $form FormModel */

use app\src\core\components\Modal;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Offre;
use app\src\model\dataObject\Roles;
use app\src\model\Form\FormModel;


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
				<input value="<?= $_GET["sujet"] ?? '' ?>" type="search" id="default-search"
				       name="sujet"
				       class="block w-full p-4 pl-10 text-sm text-zinc-900 border-2 border-zinc-200 rounded-lg bg-zinc-50 focus:ring-zinc-500 focus:border-zinc-500 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500"
				       placeholder="Rechercher une offre">
				<button type="submit" onclick="search()"
				        class="text-white absolute right-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-zinc-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-zinc-600 dark:hover:bg-zinc-700 dark:focus:ring-zinc-800">
					Rechercher
				</button>
			</div>
		</div>
	</div>
	<div class="grid grid-cols-1 gap-4 lg:grid-cols-4">
		<div class="rounded-lg p-4 border-2 border-zinc-200 w-full">
			<?php
			$form->start();
			?>
			<label>
				<input class="hidden" type="text" name="sujet" value="<?= $_GET["sujet"] ?? '' ?>">
			</label>
			<div class=" flex flex-col gap-3">
				<div class="flex justify-between items-center">
					<p class="text-md text-black font-bold">Filtres</p>
					<?php if (Auth::has_role(Roles::Staff, Roles::Manager)) { ?>
						<label for="AcceptConditions" class="relative h-5 w-10 cursor-pointer">
							<input type="checkbox" id="AcceptConditions"
							       class="peer sr-only"/>
							<span class="absolute inset-0 rounded-full  bg-zinc-800 transition border-[1px] border-zinc-100 peer-checked:bg-red-500"></span>
							<span class="absolute shadow inset-y-0 start-0 m-1 h-3 w-3 rounded-full bg-white border-[1px] border-zinc-100 transition-all peer-checked:start-5"></span>
						</label>
					<?php } ?>
				</div>
				<div class="flex flex-row w-full gap-1">
					<?php
					$form->field("type");
					?>
				</div>
				<?php
				$form->print_fields(["year", "duration", "theme", "gratification"]);
				$form->submit("Appliquer");
				$form->reset("Réinitialiser", true);
				?>
			</div>
			<?php
			$form->end();
			?>
		</div>

		<div class="lg:col-span-3 rounded-lg flex flex-col gap-4">
			<div class="flex flex-col gap-1 w-full">
				<h2 class="font-bold text-lg">Offres validées</h2>
				<div class="grid gap-4 md:grid-cols-2 lg:grid-cols-2 2xl:grid-cols-3 grid-cols-1 content-start place-items-stretch justify-items-stretch">
					<?php
					if ($offres != null) {
						foreach ($offres as $offre) {
							if ($offre["statut"] === "valider") {
								if (Auth::has_role(Roles::Manager, Roles::Staff, Roles::Teacher)) {
									require __DIR__ . '/offre.php';
								} else if (!Auth::has_role(Roles::Manager, Roles::Staff, Roles::Enterprise, Roles::Teacher, Roles::Tutor) && $offre["statut"] !== "archiver") {
									if (Application::getUser()->attributes()["annee"] == 3 && $offre["anneevisee"] == 2) {
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
						if ($offre["statut"] === "en attente" && Auth::has_role(Roles::Manager, Roles::Staff)) {
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
						if ($offre["statut"] === "archiver" && Auth::has_role(Roles::Manager, Roles::Staff)) {
							require __DIR__ . '/offre.php';
						}
					}
				}
				echo "</div>"; ?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
    function search() {
        //add parameter sujet to url
        let url = new URL(window.location.href);
        let searchParams = new URLSearchParams(url.search);
        searchParams.set("sujet", document.getElementById("default-search").value);
        url.search = searchParams.toString();

        //reload page
        window.location.href = url.toString();
    }

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



