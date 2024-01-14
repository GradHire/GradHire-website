<?php
/** @var $conventions array */

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
    if (!Auth::has_role(Roles::Manager, Roles::ManagerAlternance, Roles::ManagerStage, Roles::Staff)) {
        foreach ($conventions as $convention) {
            if (Auth::get_user()->role() == Roles::Enterprise && (new OffresRepository())->getById($convention['idoffre'])->getIdutilisateur() != Auth::get_user()->id) continue;
            elseif (Auth::get_user()->role() == Roles::Student && $convention['idutilisateur'] != Auth::get_user()->id) continue;
            $filteredConventions[] = $convention;
        }
    } else {
        $filteredConventions = $conventions;
    }


    Table::createTable($filteredConventions, ["Origine Convention", "idEtudiant", "IdOffre", "Validité Entreprise", "Validité Pédagogique"], function ($convention) {
        $getConventionValidee = $convention['conventionvalidee'];
        $getConventionValideePedagogiquement = $convention['conventionvalideepedagogiquement'];
        $getNumConvention = $convention['numconvention'];
        Table::cell($convention['origineconvention']);
        Table::cell($convention['idutilisateur']);
        Table::cell($convention['idoffre']);
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
            else
                Table::cell("déja validée");

        } else if (Auth::has_role(Roles::Enterprise)) {
            if ($getConventionValidee == "0" && $getConventionValideePedagogiquement == "1")
                Table::button("/validateConvention/" . $getNumConvention, "Valider");
            else
                Table::cell("");
        }


        Table::button("/conventions/" . $getNumConvention);
    });
    ?>
</div>
