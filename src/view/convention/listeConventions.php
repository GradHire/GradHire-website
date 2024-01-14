<?php
/** @var $conventions ConventionRepository */

use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\repository\ConventionRepository;
use app\src\model\repository\EtudiantRepository;
use app\src\model\repository\OffresRepository;
use app\src\model\repository\SoutenanceRepository;
use app\src\model\repository\VisiteRepository;
use app\src\model\View;
use app\src\view\components\ui\Table;

$this->title = 'Conventions';
View::setCurrentSection('Conventions');

?>
<div class=" overflow-x-auto w-full example  gap-4 mx-auto">
    <?php
    $filteredConventions = [];
    foreach ($conventions as $convention) {
        if (Auth::get_user()->role() == Roles::Enterprise && (new OffresRepository())->getById($convention->getIdOffre())->getIdutilisateur() != Auth::get_user()->id) continue;
        elseif (Auth::get_user()->role() == Roles::Student && $convention->getIdUtilisateur() != Auth::get_user()->id) continue;
        else {
            $filteredConventions[] = $convention;
        }
    }


    Table::createTable($filteredConventions, ["Origine Convention", "Etudiant", "IdOffre", "Validité Entreprise", "Validité Pédagogique", "Soutenance/Visite"], function ($convention) {
        $authid = Auth::get_user()->id();
        $getConventionValidee = $convention->getConventionValidee();
        $getConventionValideePedagogiquement = $convention->getConvetionValideePedagogiquement();
        $getNumConvention = $convention->getNumConvention();
        $imOneOfTheTutor = ConventionRepository::imOneOfTheTutor($authid, $getNumConvention);
        $getIfSoutenanceExist = SoutenanceRepository::getIfSoutenanceExist($getNumConvention);
        Table::cell($convention->getOrigineConvention());
        Table::cell(EtudiantRepository::getFullNameByID($convention->getIdUtilisateur()));
        Table::cell(OffresRepository::getSujetOffre($convention->getIdOffre())['sujet']);
        if ($getConventionValidee == "0") {
            Table::chip("Non valide", "yellow");
        } else if ($getConventionValidee == "1") {
            Table::chip("Validée", "green");
        }
        if ($getConventionValideePedagogiquement == "0") {
            Table::chip("Non valide", "yellow");
        } else if ($getConventionValideePedagogiquement == "1") {
            Table::chip("Validée", "green");
        }
        if (Auth::has_role(Roles::Manager, Roles::ManagerAlternance, Roles::ManagerStage, Roles::Staff)) {
            if ($getConventionValideePedagogiquement == "0")
                Table::button("/validateConventionPedagogiquement/" . $getNumConvention, "Valider");
            else if ($getConventionValideePedagogiquement == "1" && $getConventionValidee == "0")
                Table::button("/unvalidateConventionPedagogiquement/" . $getNumConvention, "Invalider");
            else if ($getConventionValidee == "1" && $getConventionValideePedagogiquement == "1" && !$getIfSoutenanceExist)
                Table::button("/createSoutenance/" . $getNumConvention, "Creer soutenance");
            else
                Table::button("/voirSoutenance/" . $getNumConvention, "Voir soutenance");
        } else if (Auth::has_role(Roles::Enterprise)) {
            if ($getConventionValidee == "0" && $getConventionValideePedagogiquement == "1")
                Table::button("/validateConvention/" . $getNumConvention, "Valider");
            else
                Table::cell("");
        }

        if (!$getIfSoutenanceExist) {
            if (Auth::has_role(Roles::TutorTeacher, Roles::Tutor) && $imOneOfTheTutor)
                Table::button("/visite/" . $getNumConvention, "Creer/Modifier Visite");
            else if (Auth::has_role(Roles::TutorTeacher, Roles::Tutor) && $getConventionValidee == "1" && $getConventionValideePedagogiquement == "1")
                Table::cell("en attente de la visite");
            else if (!Auth::has_role(Roles::ManagerAlternance,Roles::Manager,Roles::ManagerStage,Roles::Staff)){
                Table::cell("en attent de la validation");
            }
        } else if (VisiteRepository::getIfVisiteExist($getNumConvention) && !$getIfSoutenanceExist) {
            if (Auth::has_role(Roles::TutorTeacher, Roles::Tutor) && !$imOneOfTheTutor)
                Table::button("en attente de la soutenance");
            else Table::cell("en attente de la soutenance");
        }

        else
        {
            if (Auth::has_role(Roles::TutorTeacher, Roles::Teacher) && !SoutenanceRepository::getIfJuryExist($authid, $$getNumConvention)) {
                if (!SoutenanceRepository::getIfImTheTuteurProf($authid, $getNumConvention)) {
                    Table::button("/seProposerJury/" . $getNumConvention, "Etre jury");
                } else {
                    Table::cell("Déja TuteurProf");
                }
            }
            else if (Auth::has_role(Roles::TutorTeacher) && (SoutenanceRepository::imTheJury($authid, $getNumConvention) || SoutenanceRepository::imTheTuteurProf($authid, $getNumConvention))) {
                Table::button("/voirSoutenance/" . $getNumConvention, "Voir soutenance");
            } else if (Auth::has_role(Roles::Tutor) && SoutenanceRepository::imTheTuteurEntreprise($authid, $getNumConvention)) {
                Table::button("/voirSoutenance/" . $getNumConvention, "Voir soutenance");
            } else if (Auth::has_role(Roles::Student) && SoutenanceRepository::imTheEtudiant($authid, $getNumConvention)) {
                Table::button("/voirSoutenance/" . $getNumConvention, "Voir soutenance");
            } else if (!Auth::has_role(Roles::ManagerAlternance,Roles::Manager,Roles::ManagerStage,Roles::Staff)){
                Table::cell("vous n'etes pas concerné");
            }
        }
        Table::button("/conventions/" . $getNumConvention);
    });
    ?>
</div>
