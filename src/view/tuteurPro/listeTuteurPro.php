<?php
/** @var $tuteurs TuteurEntreprise */

/** @var $form FormModel */

/** @var $waiting array */


use app\src\model\Application;
use app\src\model\dataObject\TuteurEntreprise;
use app\src\model\Form\FormModel;
use app\src\model\repository\TuteurEntrepriseRepository;

$this->title = "Tuteurs";
?>
    <div class="overflow-x-auto w-full pt-12 pb-24">
<?php

$form->start();
echo '<div class="flex items-end gap-2">';
$form->field('email');
$form->submit("Ajouter");
echo '</div>';
$form->getError();
$form->getSuccess();
$form->end();

?>
    <h2 class="font-bold text-lg">Liste des tuteurs de l'entreprise</h2>
<?php
if (empty($tuteurs)) echo "<p>Aucun tuteur n'a été ajouté.</p>";
else {
    ?>
    <table class="min-w-full divide-y-2 divide-zinc-200 bg-white text-sm">
        <thead class="ltr:text-left rtl:text-right">
        <tr>
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
            $tuteur = (new TuteurEntrepriseRepository([]))->construireTuteurProDepuisTableau($tuteur);
            ?>
            <tr class="odd:bg-zinc-50">
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
                    <a href="utilisateurs/<?= $tuteur->getIdutilisateur() ?>/archiver?<?= Application::getRedirect() ?>"
                       class="text-red-500 hover:text-red-700">Supprimer</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>

    </table>

    </div>
<?php }

if (count($waiting) > 0) { ?>
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
<?php } ?>