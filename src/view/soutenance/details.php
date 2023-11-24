<?php

use app\src\model\dataObject\Soutenance;
use app\src\model\repository\StaffRepository;
use app\src\model\repository\TuteurEntrepriseRepository;
use app\src\model\repository\TuteurRepository;

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
            <?php echo $tuteurProf->getPrenom() . " " . $tuteurProf->getNomutilisateur() ?>
        </div>
    </div>
    <div class="my-8">
        <div class="font-bold text-2xl text-center">Le tuteur entreprise qui supervisera la soutenance
            sera
        </div>
        <div class="text-center mt-2">
            <?php echo $tuteurEntreprise->getPrenom() . " " . $tuteurEntreprise->getNomutilisateur() ?>
        </div>
    </div>
    <?php if ($soutenance->getIdProfesseur() !== null) { ?>
        <div class="my-8">
            <div class="font-bold text-2xl text-center ">Le professeur qui supervisera la soutenance sera
            </div>
            <div class="text-center mt-2">
                <?php echo $prof->getPrenom() . " " . $prof->getNomutilisateur() ?>
            </div>
        </div>
    <?php } else { ?>
        <div class="text-center mt-8 text-red-500">
            <?php echo "Aucun professeur n'a été désigné pour cette soutenance pour le moment" ?>
        </div>
    <?php } ?>
    <div class="flex justify-center mt-8">
        <a href="/calendar"
           class="btn btn-primary bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Retour au
            calendrier</a>
    </div>
</div>
