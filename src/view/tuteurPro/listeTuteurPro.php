<?php
/** @var $tuteurs TuteurEntreprise[] */

/** @var $form FormModel */

/** @var $waiting array */


use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\dataObject\TuteurEntreprise;
use app\src\model\Form\FormModel;
use app\src\model\repository\StaffRepository;
use app\src\model\View;
use app\src\view\components\ui\Modal;
use app\src\view\components\ui\Separator;
use app\src\view\components\ui\Table;

View::setCurrentSection('Tuteurs');
$this->title = 'Tuteurs';

$modal = new Modal("Voulez vous vraiment supprimer ce tuteur ?", "Supprimer", "");
?>
<div class=" w-full example">
    <?php

    if (Auth::has_role(Roles::Enterprise)) {
        $form->start();
        echo '<div class="flex gap-3"><div class="flex items-end gap-2 flex-grow">';
        $form->field('email');
        echo '</div>';
        echo '<div class="flex items-end">';
        $form->submit("Ajouter");
        echo '</div></div>';
        $form->getError();
        $form->getSuccess();
        $form->end();
    }


    ?>
    <h2 class="font-bold text-lg">Liste des tuteurs</h2>
    <?php
    if (empty($tuteurs)) echo "<p>Aucun tuteur</p>";
    else {
        Table::createTable($tuteurs, ["nom", "prénom", "email", "numtelephone", "fonction"], function ($tuteur) {
            Table::cell($tuteur->getNom());
            Table::cell($tuteur->getPrenom());
            Table::cell($tuteur->getEmail());
            Table::cell($tuteur->getNumtelephone());
            Table::cell($tuteur->getFonction());
            if (Auth::has_role(Roles::Manager, Roles::Staff)) Table::button("/utilisateurs/" . $tuteur->getIdutilisateur());
            else Table::button("/utilisateurs/" . $tuteur->getIdutilisateur() . "/archiver?" . Application::getRedirect());
        });
    } ?>

</div>
<?php
if (count($waiting) > 0) { ?>
    <div class="w-full ">
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
<div class=" w-full example">
    <?php Separator::render([]); ?>
    <h2 class="font-bold text-lg mt-4">Liste des tuteurs Universitaire</h2>
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

