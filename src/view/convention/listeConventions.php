<?php
/** @var $conventions ConventionRepository */

use app\src\core\components\Table;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\repository\ConventionRepository;
use app\src\model\repository\EtudiantRepository;
use app\src\model\repository\OffresRepository;
use app\src\model\repository\SoutenanceRepository;
use app\src\model\repository\VisiteRepository;

$this->title = 'Conventions';
?>
<div class="overflow-x-auto w-full gap-4 mx-auto">
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
        Table::cell($convention->getOrigineConvention());
        Table::cell((new EtudiantRepository([]))->getByIdFull($convention->getIdUtilisateur())->getPrenom() . " " . (new EtudiantRepository([]))->getByIdFull($convention->getIdUtilisateur())->getNom());
        Table::cell((new OffresRepository())->getById($convention->getIdOffre())->getSujet());
        if ($convention->getConventionValidee() == "0") {
            Table::chip("Non valide", "yellow");
        } else if ($convention->getConventionValidee() == "1") {
            Table::chip("Validée", "green");
        }
        if ($convention->getConvetionValideePedagogiquement() == "0") {
            Table::chip("Non valide", "yellow");
        } else if ($convention->getConvetionValideePedagogiquement() == "1") {
            Table::chip("Validée", "green");
        }
        if (Auth::has_role(Roles::Manager, Roles::ManagerAlternance, Roles::ManagerStage, Roles::Staff)) {
            if ($convention->getConvetionValideePedagogiquement() == "0")
                Table::button("/validateConventionPedagogiquement/" . $convention->getNumConvention(), "Valider");
            else if ($convention->getConvetionValideePedagogiquement() == "1" && $convention->getConventionValidee() == "0")
                Table::button("/unvalidateConventionPedagogiquement/" . $convention->getNumConvention(), "Invalider");
            else if ($convention->getConventionValidee() == "1" && $convention->getConvetionValideePedagogiquement() == "1" && !SoutenanceRepository::getIfSoutenanceExist($convention->getNumConvention()))
                Table::button("/createSoutenance/" . $convention->getNumConvention(), "Creer soutenance");
            else
                Table::button("/voirSoutenance/" . $convention->getNumConvention(), "Voir soutenance");
        } else if (Auth::has_role(Roles::Enterprise)) {
            if ($convention->getConventionValidee() == "0" && $convention->getConvetionValideePedagogiquement() == "1")
                Table::button("/validateConvention/" . $convention->getNumConvention(), "Valider");
            else
                Table::cell("");
        }

        if (!SoutenanceRepository::getIfSoutenanceExist($convention->getNumConvention())) {
            if (Auth::has_role(Roles::TutorTeacher, Roles::Tutor) && ConventionRepository::imOneOfTheTutor(Auth::get_user()->id(), $convention->getNumConvention()))
                Table::button("/visite/" . $convention->getNumConvention(), "Creer Visite");
            else Table::cell("en attente de la visite");
        } else if (VisiteRepository::getIfVisiteExist($convention->getNumConvention()) && !SoutenanceRepository::getIfSoutenanceExist($convention->getNumConvention())) {
            if (Auth::has_role(Roles::TutorTeacher, Roles::Tutor) && !ConventionRepository::imOneOfTheTutor(Auth::get_user()->id(), $convention->getNumConvention()))
                Table::button("en attente de la soutenance");
            else Table::cell("en attente de la soutenance");
        }

        else if (SoutenanceRepository::getIfSoutenanceExist($convention->getNumConvention()))
        {
            if (Auth::has_role(Roles::TutorTeacher, Roles::Teacher) && !SoutenanceRepository::getIfJuryExist(Application::getUser()->id(), $convention->getNumConvention())) {
                print_r("fbhreizfjreioj frehz guirepguea hguipeak");
                if (!SoutenanceRepository::getIfImTheTuteurProf(Application::getUser()->id(), $convention->getNumConvention()) && SoutenanceRepository::getIfSoutenanceExist($convention->getNumConvention())) {
                    Table::button("/seProposerJury/" . $convention->getNumConvention(), "Etre jury");
                } else {
                    Table::cell("Déja TuteurProf");
                }
            }
            else if (Auth::has_role(Roles::TutorTeacher) && (SoutenanceRepository::imTheJury(Application::getUser()->id(), $convention->getNumConvention()) || SoutenanceRepository::imTheTuteurProf(Application::getUser()->id(), $convention->getNumConvention()))) {
                Table::button("/voirSoutenance/" . $convention->getNumConvention(), "Voir soutenance");
            } else if (Auth::has_role(Roles::Tutor) && SoutenanceRepository::imTheTuteurEntreprise(Application::getUser()->id(), $convention->getNumConvention())) {
                Table::button("/voirSoutenance/" . $convention->getNumConvention(), "Voir soutenance");
            } else if (Auth::has_role(Roles::Student) && SoutenanceRepository::imTheEtudiant(Application::getUser()->id(), $convention->getNumConvention())) {
                Table::button("/voirSoutenance/" . $convention->getNumConvention(), "Voir soutenance");
            } else {
                Table::cell("");
            }
        }
        Table::button("/conventions/" . $convention->getNumConvention());
    });
    ?>
</div>