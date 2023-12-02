<?php
/** @var $tuteurs TuteurEntreprise */

/** @var $form FormModel */

/** @var $waiting array */


use app\src\core\components\Modal;
use app\src\core\components\Table;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\dataObject\TuteurEntreprise;
use app\src\model\Form\FormModel;
use app\src\model\repository\EntrepriseRepository;
use app\src\model\repository\StaffRepository;
use app\src\model\repository\TuteurEntrepriseRepository;

$this->title = "Tuteurs";

$modal = new Modal("Voulez vous vraiment supprimer ce tuteur ?", "Supprimer", "");
?>
<div class="overflow-x-auto w-full pt-12">
    <?php

    if (Auth::has_role(Roles::Enterprise)) {
        $form->start();
        echo '<div class="flex items-end gap-2">';
        $form->field('email');
        $form->submit("Ajouter");
        echo '</div>';
        $form->getError();
        $form->getSuccess();
        $form->end();
    }


    ?>
    <h2 class="font-bold text-lg">Liste des tuteurs</h2>
    <?php
    if (empty($tuteurs))
        echo "<p>Aucun tuteur</p>";
    else {
        Table::createTable($tuteurs, ["nom", "prénom", "email", "numtelephone", "fonction"], function ($tuteur) {
            $tuteur = (new TuteurEntrepriseRepository([]))->construireTuteurProDepuisTableau($tuteur);
            Table::cell($tuteur->getNom());
            Table::cell($tuteur->getPrenom());
            Table::cell($tuteur->getEmail());
            Table::cell($tuteur->getNumtelephone());
            Table::cell($tuteur->getFonction());
            if (Auth::has_role(Roles::Manager, Roles::Staff)) {
                Table::button("/utilisateurs/" . $tuteur->getIdutilisateur());
            } else {
                Table::button("/utilisateurs/" . $tuteur->getIdutilisateur() . "/archiver?" . Application::getRedirect());
            }
        });
    } ?>

</div>
<?php
if (count($waiting) > 0) { ?>
    <div class="overflow-x-auto w-full pt-6">
        <h2 class="font-bold text-lg mt-4">Liste des tuteurs en attente</h2>
        <?php
        Table::createTable($waiting, ["email"], function ($tuteur) {
            Table::cell($tuteur["email"]);
        });
        ?>
    </div>
<?php }
if (Auth::has_role(Roles::Manager, Roles::Staff)) {
?>
<div class="overflow-x-auto w-full pt-12">
    <h2 class="font-bold text-lg">Liste des tuteurs Universitaire</h2>
    <?php
    $tuteurs = new StaffRepository([]);
    $tuteurs = $tuteurs->getAllTuteurProf();
    if ($tuteurs == null) {
        echo "<p>Aucun tuteur Universitaire trouvé</p>";
    } else {
        Table::createTable($tuteurs, ["nom", "prénom", "email", "numtelephone", "bio", "role", "tuteur entreprsie"], function ($tuteur) {
            Table::cell($tuteur->getNom());
            Table::cell($tuteur->getPrenom());
            Table::cell($tuteur->getEmail());
            Table::cell($tuteur->getNumtelephone());
            Table::cell($tuteur->getBio());
            Table::cell($tuteur->getRole());
            Table::cell($tuteur->getIdtuteurentreprise());
            Table::button("/utilisateurs/" . $tuteur->getIdutilisateur());
        });
    }
    }
    ?>
</div>

