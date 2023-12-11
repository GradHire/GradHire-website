<?php
/** @var $listProf */
?>
<?php

if ($listProf == null) {
    echo "<p class='text-center text-2xl'>Aucun professeur n'a été trouvé</p>";
    echo "<button type='button' id='revenir' class='text-center text-2xl'>Revenir à la recherche</button>";
} else {
    $prof = $listProf;
    ?>
    <div class="overflow-x-auto w-full gap-4 mx-auto">
        <table class="min-w-full divide-y-2 divide-zinc-200 bg-white text-sm">
            <thead class="ltr:text-left rtl:text-right">
            <tr>
                <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                    Nom du professeur
                </th>
                <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                    Prénom du professeur
                </th>
                <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                    Téléphone
                </th>
                <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                    Mail
                </th>
            </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200">
            <?php
            if ($prof != null) { ?>
                <tr class="odd:bg-zinc-50">
                    <td class="whitespace-nowrap px-4 py-2 font-medium text-zinc-900">
                        <?php
                        if ($prof->getNom() !== null) echo $prof->getNom();
                        else echo("Non renseigné");
                        ?>
                    </td>
                    <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                        <?php
                        $prenom = $prof->getPrenom();
                        if ($prenom != null) echo $prenom;
                        else echo("Non renseigné");
                        ?>
                    </td>
                    <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                        <?php
                        if ($prof->getNumtelephone() == null) echo("Non renseigné");
                        else echo $prof->getNumtelephone(); ?>
                    </td>
                    <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                        <?php
                        if ($prof->getEmail() == null) echo("Non renseigné");
                        else echo $prof->getEmail();
                        ?>
                    </td>
                    <td class="whitespace-nowrap px-4 py-2">
                        <a href="/simulateurSignataire?idProfRef=<?= $prof->getIdutilisateur() ?>">Choisir</a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <button id="revenir" type="button">Revenir en arrière</button>
    </div>
    <?php
}
?>
<script>
    document.getElementById('revenir').addEventListener('click', function () {
        window.location.href = 'simulateurProfReferent';
    });
</script>
