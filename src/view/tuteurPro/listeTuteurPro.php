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
    ?>
    <table class="min-w-full divide-y-2 divide-zinc-200 bg-white text-sm">
        <thead class="ltr:text-left rtl:text-right">
        <tr>
            <?php if (Auth::has_role(Roles::Manager, Roles::Staff)) { ?>
                <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                    Entreprise
                </th>
            <?php } ?>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                Prénom
            </th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                Nom
            </th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                Email
            </th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                Téléphone
            </th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                Fonction
            </th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">

            </th>
        </tr>
        </thead>

        <tbody class="divide-y divide-zinc-200">
        <?php


        foreach ($tuteurs as $tuteur) {
            ?>
            <tr class="odd:bg-zinc-50">
                <?php if (Auth::has_role(Roles::Manager, Roles::Staff)) {
                    $tuteur = (new TuteurEntrepriseRepository([]))->construireTuteurProDepuisTableau($tuteur);
                    ?>
                    <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                        <?= (new EntrepriseRepository([]))->getByIdFull($tuteur->getIdentreprise())->getNomutilisateur() ?>
                    </td>
                <?php } ?>
                <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                    <?= $tuteur->getPrenom(); ?>
                </td>
                <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                    <?= $tuteur->getNomutilisateur(); ?>
                </td>
                <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                    <?= $tuteur->getEmailutilisateur(); ?>
                </td>
                <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                    <?= $tuteur->getNumtelutilisateur(); ?>
                </td>
                <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                    <?= $tuteur->getFonction(); ?>
                </td>
                <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                    <?php if (Auth::has_role(Roles::Manager, Roles::Staff)) { ?>
                        <a href="utilisateurs/<?= $tuteur->getIdutilisateur() ?>"
                           class="inline-block rounded bg-zinc-600 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700">Voir
                            plus</a>
                    <?php } else { ?>
                        <p <?= $modal->Show("utilisateurs/" . $tuteur->getIdutilisateur() . "/archiver?" . Application::getRedirect()) ?>
                                class="text-red-500 hover:text-red-700">Supprimer</p>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>

    </table>

</div>
<?php }

if (count($waiting) > 0) { ?>
    <div class="overflow-x-auto w-full pt-6">
        <h2 class="font-bold text-lg mt-4">Liste des tuteurs en attente</h2>
        <table class="min-w-full divide-y-2 divide-zinc-200 bg-white text-sm">
            <thead class="ltr:text-left rtl:text-right">
            <tr>
                <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                    Email
                </th>
            </tr>
            </thead>

            <tbody class="divide-y divide-zinc-200">
            <?php
            foreach ($waiting as $tuteur) { ?>
                <tr class="odd:bg-zinc-50">
                    <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                        <?= $tuteur["email"] ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
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
        Table::createTable($tuteurs, ["nom", "prénom", "email", "numtelephone", "bio", "role"], function ($tuteur) {
            Table::cell($tuteur->getNomutilisateur());
            Table::cell($tuteur->getPrenom());
            Table::cell($tuteur->getEmailutilisateur());
            Table::cell($tuteur->getNumtelutilisateur());
            Table::cell($tuteur->getBio());
            Table::cell($tuteur->getRole());
            Table::button("/utilisateurs/" . $tuteur->getIdutilisateur());
        });
    }
    }
    ?>
</div>

