<?php
/** @var $listTuteur ?array */

use app\src\model\repository\TuteurEntrepriseRepository;


?>
<?php

if ($listTuteur == null) {
    echo "<p class='text-center text-2xl'>Aucun Tuteur n'a été trouvée pour cette entreprise</p>";
} else {
?>
<div class="overflow-x-auto w-full pt-12 pb-24">
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
                Fonction
            </th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                Tel/Mail
            </th>
        </tr>
        </thead>

        <tbody class="divide-y divide-zinc-200">
        <?php
        foreach ($listTuteur as $tuteur) { ?>
            <tr class="odd:bg-zinc-50">
                <td class="whitespace-nowrap px-4 py-2 font-medium text-zinc-900">
                    <?php

                    if ($tuteur->getNom() == null) echo("Non renseigné");
                    else echo $tuteur->getNom()
                    ?>
                </td>
                <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                    <?php
                    if ($tuteur->getPrenom() == null) echo("Non renseigné");
                    else echo $tuteur->getPrenom();
                    ?>
                </td>
                <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                    <?php
                    if ($tuteur->getFonction() == null) echo("Non renseigné");
                    else echo $tuteur->getFonction(); ?>
                </td>
                <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                    <?php
                    if ($tuteur->getNumtelephone() == null) echo("Non renseigné");
                    else echo("Tel : " . $tuteur->getNumtelephone());
                    if ($tuteur->getEmail() == null) echo("Non renseigné");
                    else echo(" / Mail : " . $tuteur->getEmail());
                    ?>


                </td>
                <td class="whitespace-nowrap px-4 py-2">
                    <a href="/simulateurCandidature?idTuteur=<?= $tuteur->getIdutilisateur() ?>">Choisir</a>
                </td>
            </tr>
        <?php }
        } ?>
        </tbody>
    </table>
    <div class="flex">
        <p> Le tuteur n'existe pas encore </p>
        <a href="/creerTuteur"
           class="ml-3 inline-block rounded bg-zinc-600 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700">Créer-le</a>
    </div>
</div>
