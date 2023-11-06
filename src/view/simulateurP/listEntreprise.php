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
                            $email = $entreprise->();
                            if ($email != null) echo $email;
                            else echo("Non renseigné");
                            ?>
                        </td>
                        <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                            <?php
                            if ($entreprise->getNumtelutilisateur() == null) echo("Non renseigné");
                            else echo $entreprise->getNumtelutilisateur();
                            ?>
                        </td>
                        <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                            <?php
                            if ($entreprise->getSiteweb() == null) echo("Non renseigné");
                            else echo $entreprise->getSiteweb(); ?>
                        </td>
                        <td class="whitespace-nowrap px-4 py-2">
                            <a href="/entreprises/<?= $entreprise->getIdutilisateur(); ?>"
                               class="inline-block rounded bg-zinc-600 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700">Voir
                                plus</a>
                        </td>
                    </tr>
                <?php }
            } ?>
            </tbody>

        </table>
    </div>
    <?php
}
?>
<script>
    document.getElementById('revenir').addEventListener('click', function () {
        window.location.href = 'simulateurOffre';
    });
</script>