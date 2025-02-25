<?php

/** @var $entreprise Entreprise
 * @var $offres Offre
 * @var $avisPublic Avis
 * @var $avisPriver Avis
 */

use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Avis;
use app\src\model\dataObject\Entreprise;
use app\src\model\dataObject\Offre;
use app\src\model\dataObject\Roles;
use app\src\model\repository\AvisRepository;
use app\src\view\components\ui\Detail;
use app\src\view\components\ui\Table;

$nom = $entreprise->getNom();
if (empty($nom) || $nom == "") $nom = "Sans nom";

?>
<div class="w-full gap-4 mx-auto">
    <div class="w-full flex md:flex-row flex-col  justify-between items-start">
        <div class="px-4 sm:px-0">
            <h3 class="text-lg font-semibold leading-7 text-zinc-900"><?= $nom ?></h3>
            <p class="mt-1 max-w-2xl text-sm leading-6 text-zinc-500">
                Structure: <?= $entreprise->getTypestructure() ?? "" ?></p>
        </div>
    </div>
    <?php
    Detail::start();
    Detail::addDetail("Effectif", $entreprise->getEffectif());
    Detail::addDetail("Code NAF", $entreprise->getCodenaf());
    Detail::addDetail("Fax", $entreprise->getFax());
    Detail::addDetailLink("Site web", $entreprise->getSiteweb());
    Detail::addDetail("Numéro de téléphone", $entreprise->getNumtelephone());
    Detail::addDetail("Email", $entreprise->getEmail());
    Detail::addDetail("SIRET", $entreprise->getSiret());
    Detail::addDetail("Statut juridique", $entreprise->getStatutjuridique());
    if (Auth::has_role(Roles::ManagerAlternance, Roles::Manager, Roles::TutorTeacher, Roles::Student, Roles::ManagerStage, Roles::Teacher, Roles::Staff)) {
        $avisPublic = AvisRepository::getAvisEntreprisePublic($entreprise->getIdutilisateur());
        $avisPriver = AvisRepository::getAvisEntreprisePriver($entreprise->getIdutilisateur());
        if ($avisPublic != null) {
            Detail::addDetailAvis("Avis Public sur l'entreprise : ", $avisPublic);
        }
        if ($avisPriver != null && Auth::has_role(Roles::ManagerAlternance, Roles::Manager, Roles::TutorTeacher, Roles::ManagerStage, Roles::Teacher, Roles::Staff)) {
            Detail::addDetailAvis("Avis Privé sur l'entreprise : ", $avisPriver);
        }
    }
    if (Auth::has_role(Roles::TutorTeacher, Roles::Teacher, Roles::Manager, Roles::ManagerAlternance, Roles::ManagerStage, Roles::Student) && AvisRepository::checkIfAvisPosted($entreprise->getIdutilisateur(), Application::getUser()->id())) {
        echo '<a href="/entreprises/' . $entreprise->getIdutilisateur() . '/modifierAvis" class="inline-block rounded  px-4 py-2 text-xs font-medium text-white bg-zinc-800 hover:bg-zinc-900">Modifier l\'avis</a>';
    } else if (Auth::has_role(Roles::TutorTeacher, Roles::Teacher, Roles::Manager, Roles::ManagerAlternance, Roles::ManagerStage, Roles::Student)) {
        echo '<a href="/entreprises/' . $entreprise->getIdutilisateur() . '/posterAvis" class="inline-block rounded  px-4 py-2 text-xs font-medium text-white bg-zinc-800 hover:bg-zinc-900">Poster un avis</a>';

    }

    if ($offres != null) {
        Detail::space();
        Table::createTable($offres, ['sujet', 'thematique', 'dateCreation', 'status'], function ($offre) {
            Table::cell($offre["sujet"]);
            Table::cell($offre["thematique"]);
            Table::cell($offre["datecreation"]);
            $statut = $offre["statut"];
            if ($statut == "en attente") Table::chip("En attente", "yellow");
            else if ($statut == "valider") Table::chip("Validée", "green");
            else if ($statut == "archiver") Table::chip("Archivée", "red");
            else if ($statut == "brouillon") Table::chip("Brouillon", "gray");
            Table::cell("<a href=\"/offres/" . $offre["idoffre"] . "\" class=\"inline-block rounded bg-zinc-600 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700\">Voir plus</a>");
        });
    }
    Detail::end();
    ?>
</div>
