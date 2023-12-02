<?php

use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\dataObject\Soutenance;
use app\src\model\repository\NotesRepository;
use app\src\model\repository\StaffRepository;
use app\src\model\repository\TuteurEntrepriseRepository;

/** @var $soutenance Soutenance */
$tuteurProf = $soutenance->getIdTuteurProf();
$tuteurProf = (new StaffRepository([]))->getByIdFull($tuteurProf);
$tuteurEntreprise = $soutenance->getIdTuteurEnterprise();
$tuteurEntreprise = (new TuteurEntrepriseRepository([]))->getById($tuteurEntreprise);
if ($soutenance->getIdProfesseur() !== null) {
    $prof = $soutenance->getIdProfesseur();
    $prof = (new StaffRepository([]))->getByIdFull($prof);
}

?>
<div class="w-full flex flex-col justify-center content-center mt-5">
    <div class="gap-4 mb-3 text-center">
        <div class="font-bold text-2xl ">Soutenance correspondant au numéro de
            convention <?php echo $soutenance->getNumConvention() ?></div>
    </div>
    <div class="text-center text-gray-600">
        Commencera le <?php echo $soutenance->getDebutSoutenance()->format("d/m/Y à H:i") ?> Et se terminera
        le <?php echo $soutenance->getFinSoutenance()->format("d/m/Y à H:i") ?>
    </div>
    <div class="my-8">
        <div class="font-bold text-2xl text-center">Le tuteur professeur qui supervisera la soutenance
            sera
        </div>
        <div class="text-center mt-2">
            <?php echo $tuteurProf->getPrenom() . " " . $tuteurProf->getNom() ?>
        </div>
    </div>
    <div class="my-8">
        <div class="font-bold text-2xl text-center">Le tuteur entreprise qui supervisera la soutenance
            sera
        </div>
        <div class="text-center mt-2">
            <?php echo $tuteurEntreprise->getPrenom() . " " . $tuteurEntreprise->getNom() ?>
        </div>
    </div>
    <?php if ($soutenance->getIdProfesseur() !== null) { ?>
        <div class="my-8">
            <div class="font-bold text-2xl text-center ">Le professeur qui supervisera la soutenance sera
            </div>
            <div class="text-center mt-2">
                <?php echo $prof->getPrenom() . " " . $prof->getNom() ?>
            </div>
        </div>
    <?php } else { ?>
        <div class="text-center mt-8 text-red-500">
            <?php echo "Aucun professeur n'a été désigné pour cette soutenance pour le moment" ?>
        </div>
    <?php }
    if ($soutenance->getFinSoutenance() < new DateTime() && Auth::has_role(Roles::Staff, Roles::Teacher, Roles::TutorTeacher, Roles::Manager, Roles::Student)) {
        $existnote = (new NotesRepository([]))->getById($soutenance->getIdSoutenance());
        if ($existnote === null && !Auth::has_role(Roles::Student)) {
            ?>
            <div class="flex justify-center mt-8">
                <a href="/noteSoutenance/<?php echo $soutenance->getNumConvention() ?>"
                   class="btn btn-primary bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Noter La
                    soutenance</a>
            </div>
            <?php
        } else {
            ?>
            <h3 class="font-bold text-center mt-5">Les notes de la soutenances sont :</h3>
            <div class="w-full flex justify-center mt-2">
                <ul class=" list-disc ml-6 ">
                    <li><?php echo $existnote->getNotedemarche() ?> pour la démarche</li>
                    <li><?php echo $existnote->getNoterapport() ?> pour le rapport</li>
                    <li><?php echo $existnote->getNoteoral() ?> pour l'oral</li>
                    <li><?php echo $existnote->getNoterelation() ?> pour les relations interpersonnelles</li>
                    <li><?php echo $existnote->getNoteresultat() ?> pour le résultat</li>
                </ul>
            </div>
            <?php
        }
        ?>
        <?php
    }
    ?>
    <div class="flex justify-center mt-8">
        <a href="/calendar"
           class="btn btn-primary bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Retour au
            calendrier</a>
    </div>
</div>
