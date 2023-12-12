<?php
/** @var $entreprises Entreprise[] */

use app\src\core\components\Table;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\repository\AvisRepository;
use app\src\model\dataObject\Entreprise;
use app\src\model\dataObject\Roles;

$this->title = 'Entreprises';

?>
<div class="overflow-x-auto w-full pt-12 pb-24">
    <?php
    Table::createTable($entreprises, ["Nom d'entreprise", "Email", "Téléphone", "Site web"], function ($entreprise) {
        Table::cell($entreprise->getNom());
        Table::mail($entreprise->getEmail());
        Table::phone($entreprise->getNumtelephone());
        Table::link($entreprise->getSiteWeb());
        Table::button("/entreprises/" . $entreprise->getIdutilisateur());
        if (Auth::has_role(Roles::TutorTeacher, Roles::Teacher,Roles::Manager,Roles::ManagerAlternance,Roles::ManagerStage) && AvisRepository::checkIfAvisPosted($entreprise->getIdutilisateur(), Application::getUser()->id())) {
            Table::button("/entreprises/" . $entreprise->getIdutilisateur() . "/modifierAvis", "Modifier l'avis");
        } else if (Auth::has_role(Roles::TutorTeacher, Roles::Teacher,Roles::Manager,Roles::ManagerAlternance,Roles::ManagerStage)){
            Table::button("/entreprises/" . $entreprise->getIdutilisateur() . "/posterAvis", "Poster un avis");
        }
    });
    ?>
</div>