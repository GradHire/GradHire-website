<?php

// Assuming all your PHP code and classes at the top are unchanged and necessary for this execution.
use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\dataObject\Soutenance;
use app\src\model\repository\NotesRepository;
use app\src\model\repository\StaffRepository;
use app\src\model\repository\TuteurEntrepriseRepository;
use app\src\model\View;

/** @var $soutenance Soutenance */
/** @var $commentaires array */

$tuteurProf = $soutenance->getIdTuteurProf();
$tuteurProf = (new StaffRepository([]))->getByIdFull($tuteurProf);
$tuteurEntreprise = $soutenance->getIdTuteurEnterprise();
$tuteurEntreprise = (new TuteurEntrepriseRepository([]))->getById($tuteurEntreprise);

if ($soutenance->getIdProfesseur() !== null) {
    $prof = $soutenance->getIdProfesseur();
    $prof = (new StaffRepository([]))->getByIdFull($prof);
}

$this->title = 'Soutenance';
View::setCurrentSection('Soutenances');

?>

<div class="w-full gap-2 mx-auto flex flex-col md:py-[50px] border bg-white p-2 md:p-4 rounded-md drop-shadow-[10px]">
    <div class="mb-4 text-center">
        <h1 class="md:text-3xl text-xl font-bold text-zinc-900">
            Détails Soutenance Convention No. <?= htmlspecialchars($soutenance->getNumConvention()) ?>
        </h1>
        <p class="md:text-xl text-md text-zinc-600 mt-2">
            Du <?= htmlspecialchars($soutenance->getDebutSoutenance()->format("d/m/Y à H:i")) ?>
            au <?= htmlspecialchars($soutenance->getFinSoutenance()->format("d/m/Y à H:i")) ?>
        </p>
    </div>

    <div class="grid md:grid-cols-2 gap-2 md:gap-6">
        <div class="bg-zinc-100 rounded-md p-4 border">
            <h2 class="text-lg font-semibold text-zinc-700 mb-2 text-center">Tuteur Professeur</h2>
            <p class="text-center"><?= htmlspecialchars($tuteurProf->getPrenom() . " " . $tuteurProf->getNom()) ?></p>
        </div>

        <div class="bg-zinc-100 rounded-md p-4 border">
            <h2 class="text-lg font-semibold text-zinc-700 mb-2 text-center">Tuteur Entreprise</h2>
            <p class="text-center"><?= htmlspecialchars($tuteurEntreprise->getPrenom() . " " . $tuteurEntreprise->getNom()) ?></p>
        </div>

        <?php if ($soutenance->getIdProfesseur() !== null) : ?>
            <div class="bg-zinc-100 rounded-md p-4 shadow">
                <h2 class="text-lg font-semibold text-zinc-700 mb-2">Professeur Superviseur:</h2>
                <p><?= htmlspecialchars($prof->getPrenom() . " " . $prof->getNom()) ?></p>
            </div>
        <?php else : ?>
    </div>
    <div class="text-red-500 text-center my-4 w-full">
        Aucun professeur n'a été désigné pour cette soutenance pour le moment
    </div>
    <?php endif; ?>
    <?php
    foreach ($commentaires as $commentaire) {
        if ($commentaire['commentairesoutenanceprof'] != null) {
            echo <<<HTML
                <div class="text-center flex flex-col gap-1 mb-4">
                <span class="font-bold">Tuteur universitaire :</span>
                 <p>{$commentaire['commentairesoutenanceprof']}</p>
            </div>
            HTML;
        }
        if ($commentaire['commentairesoutenanceentreprise'] != null) {
            echo <<<HTML
                <div class="text-center flex flex-col gap-1 mb-4">
                <span class="font-bold">Tuteur entreprise :</span>
                 <p>{$commentaire['commentairesoutenanceentreprise']}</p>
            </div>
            HTML;
        }
    }
    if ($soutenance->getFinSoutenance() < new DateTime() && Auth::has_role(Roles::Staff, Roles::Teacher, Roles::TutorTeacher, Roles::Manager, Roles::Student)) {
        $existnote = (new NotesRepository([]))->getById($soutenance->getIdSoutenance());
        if (count($commentaires) > 0) {
            ?>
            <h2 class="text-center font-bold text-2xl mb-4">Commentaires sur la soutenance</h2>
            <?php
            foreach ($commentaires as $commentaire) {
                if ($commentaire['commentairesoutenanceprof'] !== null) {
                    echo <<<HTML
                <div class="text-center flex flex-col gap-1 mb-4">
                <span class="font-bold">Tuteur universitaire :</span>
                 <p>{$commentaire['commentairesoutenanceprof']}</p>
            </div>
            HTML;
                }
                if ($commentaire['commentairesoutenanceentreprise'] != null) {
                    echo <<<HTML
                <div class="text-center flex flex-col gap-1 mb-4">
                <span class="font-bold">Tuteur entreprise :</span>
                 <p>{$commentaire['commentairesoutenanceentreprise']}</p>
            </div>
            HTML;
                }
            }
        }
        if ($existnote === null && !Auth::has_role(Roles::Student)) {
            ?>
            <div class="w-full gap-2 md:gap-6 flex md:flex-row flex-col">
                <a href="/calendar"
                   class="btn btn-primary bg-blue-500 hover:bg-blue-600 text-center py-2 px-4 text-white rounded-md w-full">
                    Retour au calendrier
                </a>
                <a href="/noteSoutenance/<?php echo $soutenance->getNumConvention() ?>"
                   class="btn btn-primary bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md text-center w-full">Noter
                    la
                    soutenance</a>
            </div>
            <?php
        } else if ($existnote !== null && $existnote->getValide() == 1) {
            ?>
            <h3 class=" text-center mt-5">La notes de la soutenances est :</h3>
            <div class="w-full flex justify-center mt-2">
                <ul class=" list-disc ml-6 ">
                    <li><?php echo $existnote->noteFinal() ?>/20</li>
                </ul>
            </div>
            <?php
        } else {
            ?>
            <div class="text-center text-red-500">
                <?php echo "La soutenance n'a pas encore été notée" ?>
            </div>
            <?php
        }
    }
    ?>
</div>
