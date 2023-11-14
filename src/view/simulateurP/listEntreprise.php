<?php
/** @var $listEntreprise ?array */
?>
<?php

if ($listEntreprise == null) {
    echo "<p class='text-center text-2xl'>Aucune entreprise n'a été trouvée</p>";
    //fais un bouton
    echo "<button type='button' id='revenir' class='text-center text-2xl'>Revenir à la recherche (ou créer l'entreprise)</button>";
} else {
    ?>
    <div class="overflow-x-auto w-full pt-12 pb-24">
        <table class="min-w-full divide-y-2 divide-zinc-200 bg-white text-sm">
            <thead class="ltr:text-left rtl:text-right">
            <tr>
                <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                    Nom d'entreprise
                </th>
                <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                    Numéro Siret
                </th>
                <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                    Adresse
                </th>
                <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                    Code Postal
                </th>
                <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                    Commune
                </th>
                <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                    Pays
                </th>
            </tr>
            </thead>

            <tbody class="divide-y divide-zinc-200">
            <?php
            if ($listEntreprise != null) {
                foreach ($listEntreprise as $entreprise) { ?>
                    <tr class="odd:bg-zinc-50">
                        <td class="whitespace-nowrap px-4 py-2 font-medium text-zinc-900">
                            <?= $entreprise->getNomutilisateur(); ?>
                        </td>
                        <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                            <?php
                            $siret = $entreprise->getSiret();
                            if ($siret != null) echo $siret;
                            else echo("Non renseigné");
                            ?>
                        </td>
                        <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                            <?php
                            if ($entreprise->getAdresse() == null) echo("Non renseigné");
                            else echo $entreprise->getAdresse(); ?>
                        </td>
                        <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                            <?php
                            if ($entreprise->getCodePostal() == null) echo("Non renseigné");
                            else echo $entreprise->getCodePostal();
                            ?>
                        </td>
                        <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                            <?php
                            if ($entreprise->getVille() == null) echo("Non renseigné");
                            else echo $entreprise->getVille(); ?>
                        </td>
                        <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                            <?php
                            if ($entreprise->getPays() == null) echo("Non renseigné");
                            else echo $entreprise->getPays(); ?>
                        </td>
                        <td class="whitespace-nowrap px-4 py-2">
                            <a href="/previewOffre?idEntreprise=<?= $entreprise->getIdutilisateur() ?>">Choisir</a>
                        </td>
                    </tr>
                <?php }
            } ?>
            </tbody>
        </table>
        <button id="revenir" type="button">Revenir en arrière</button>
    </div>
    <?php
}
?>
<script>
    document.getElementById('revenir').addEventListener('click', function () {
        window.location.href = 'simulateurOffre';
    });

</script>