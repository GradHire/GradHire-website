<?php
/** @var $postulations array */

/** @var $idOffre \app\src\model\dataObject\Offre */

use app\src\model\repository\StaffRepository;
use app\src\model\repository\TuteurRepository;

?>

<div class="overflow-x-auto w-full gap-4 mx-auto">
    <table class="min-w-full divide-y-2 divide-zinc-200 bg-white text-sm">
        <thead class="ltr:text-left rtl:text-right">
        <tr>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                IdUtilisateur
            </th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                Prenom tuteur
            </th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                Nom tuteur
            </th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                Email tuteur
            </th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                idOffre
            </th>
        </tr>
        </thead>

        <tbody class="divide-y divide-zinc-200">
        <?php
        if (!empty($postulations)) {
        foreach ($postulations

        as $prof){
        $professeur = (new StaffRepository([]))->getByIdFull($prof["idutilisateur"]);
        ?>
        <tr class="odd:bg-zinc-50">
            <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                <?php
                $id = $professeur->getIdUtilisateur();
                if ($id != null) echo $id;
                else echo("Non renseigné");
                ?>
            </td>
            <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                <?php
                $prenom = $professeur->getPrenom();
                if ($prenom != null) echo $prenom;
                else echo("Non renseigné");
                ?>
            </td>
            <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                <?php
                $nom = $professeur->getNom();
                if ($nom != null) echo $nom;
                else echo("Non renseigné");
                ?>
            </td>
            <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                <?php
                $mail = $professeur->getEmail();
                if ($mail != null) echo $mail;
                else echo("Non renseigné");
                ?>
            </td>
            <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                <?php
                if ($idOffre != null) echo $idOffre;
                else echo("Non renseigné");
                ?>
            </td>
            <?php
            if (!(new TuteurRepository([]))->getIfTuteurAlreadyExist($professeur->getIdutilisateur(), $idOffre, $prof['idetudiant'])) {
                ?>
                <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                    <a href="/postuler/accepter_as_tuteur/<?php echo $professeur->getIdUtilisateur(); ?>/<?php echo $idOffre; ?>/<?php echo $prof['idetudiant']; ?>"
                       class="flex w-full rounded bg-green-600 px-4 py-2 text-xs font-medium text-white hover:bg-green-700 justify-center">
                        Accepter comme tuteur </a>
                </td>
                <?php
            } elseif ((new TuteurRepository([]))->getIfTuteurAlreadyExist($professeur->getIdutilisateur(), $idOffre, $prof['idetudiant'])) {
                ?>
                <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                    <a href="/postuler/unaccepte_as_tuteur/<?php echo $professeur->getIdUtilisateur(); ?>/<?php echo $idOffre; ?>/<?php echo $prof['idetudiant']; ?>"
                       class="flex w-full rounded bg-orange-600 px-4 py-2 text-xs font-medium text-white hover:bg-orange-700 justify-center">
                        Annuler Validation </a>
                </td>
                <?php
            }
            ?>
            <?php if (empty($postulations)) {
                echo "<h2>Ils n'y a aucun tuteurs pour cette postulation.</h2>";
            }
            }
            } ?>

</div>
