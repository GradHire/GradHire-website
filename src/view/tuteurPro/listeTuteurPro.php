<?php
/** @var $tuteurs \app\src\model\dataObject\TuteurPro */

use app\src\model\repository\TuteurProRepository;

?>
<div class="overflow-x-auto w-full pt-12 pb-24">
<?php
if(empty($tuteurs)) echo'<h2> Pas de Tuteurs Pro </h2>';
else{?> <h2 class="font-bold text-lg">Liste des Tuteurs Pro de l'entreprise</h2>
    <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
        <thead class="ltr:text-left rtl:text-right">
        <tr>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-gray-900">
                Nom Tuteur Pro
            </th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-gray-900">
                Prénom Tuteur Pro
            </th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-gray-900">
                Email
            </th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-gray-900">
                Téléphone
            </th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-gray-900">
                Fonction
            </th>
        </tr>
        </thead>

        <tbody class="divide-y divide-gray-200">
        <?php


        foreach ($tuteurs as $tuteur) {
            $tuteur=(new TuteurProRepository())->construireTuteurProDepuisTableau($tuteur);?>
            <tr class="odd:bg-gray-50">
                <td class="whitespace-nowrap px-4 py-2 text-gray-700">
                    <?= $tuteur->getNomutilisateur(); ?>
                </td>
                <td class="whitespace-nowrap px-4 py-2 text-gray-700">
                    <?php
                    $prenom = $tuteur->getPrenomtuteurp();
                    if ($prenom != null) echo $prenom;
                    else echo("Non renseigné");
                    ?>
                </td>
                <td class="whitespace-nowrap px-4 py-2 text-gray-700">
                    <?php
                    $email = $tuteur->getEmailutilisateur();
                    if ($email != null) echo $email;
                    else echo("Non renseigné");
                    ?>
                </td>
                <td class="whitespace-nowrap px-4 py-2 text-gray-700">
                    <?php
                    if ($tuteur->getNumtelutilisateur() == null) echo("Non renseigné");
                    else echo $tuteur->getNumtelutilisateur();
                    ?>
                </td>
                <td class="whitespace-nowrap px-4 py-2 text-gray-700">
                    <?php
                    if ($tuteur->getFonctiontuteurp() == null) echo("Non renseigné");
                    else echo $tuteur->getFonctiontuteurp(); ?>
                </td>
                <td class="whitespace-nowrap px-4 py-2">
                    <?php $tuteur->getIdutilisateur(); ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>

    </table>
</div>
<?php } ?>