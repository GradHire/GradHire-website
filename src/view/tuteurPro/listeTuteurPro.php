<?php
/** @var $tuteurs TuteurEntreprise */

/** @var $form FormModel */

/** @var $waiting array */

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

if (empty($tuteurs)) echo '<h2> Pas de Tuteurs Pro </h2>';
else {
    ?> <h2 class="font-bold text-lg">Liste des tuteurs de l'entreprise</h2>
    <table class="min-w-full divide-y-2 divide-zinc-200 bg-white text-sm">
        <thead class="ltr:text-left rtl:text-right">
        <tr>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                Nom
            </th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                Prénom
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
        </tr>
        </thead>

        <tbody class="divide-y divide-zinc-200">
        <?php


        foreach ($tuteurs as $tuteur) {
            $tuteur = (new TuteurEntrepriseRepository([]))->construireTuteurProDepuisTableau($tuteur); ?>
            <tr class="odd:bg-zinc-50">
                <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                    <?= $tuteur->getNomutilisateur(); ?>
                </td>
                <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                    <?php
                    $prenom = $tuteur->getPrenom();
                    if ($prenom != null) echo $prenom;
                    else echo("Non renseigné");
                    ?>
                </td>
                <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                    <?php
                    $email = $tuteur->getEmailutilisateur();
                    if ($email != null) echo $email;
                    else echo("Non renseigné");
                    ?>
                </td>
                <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                    <?php
                    if ($tuteur->getNumtelutilisateur() == null) echo("Non renseigné");
                    else echo $tuteur->getNumtelutilisateur();
                    ?>
                </td>
                <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                    <?php
                    if ($tuteur->getFonction() == null) echo("Non renseigné");
                    else echo $tuteur->getFonction(); ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>

    </table>
    <?php if (count($waiting) > 0) {
        ?> <h2 class="font-bold text-lg mt-4">Liste des tuteurs en attente</h2>
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
    </div>
<?php }
