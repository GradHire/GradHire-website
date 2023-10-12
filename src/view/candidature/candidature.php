<?php
/** @var $candidatures \app\src\model\dataObject\Candidature */

use app\src\model\dataObject\Entreprise;
use app\src\model\repository\EntrepriseRepository;
use app\src\model\repository\OffresRepository;
use app\src\model\repository\UtilisateurRepository;

$offresRepo= new OffresRepository();
global $etatCandidature;

?>
<div class="overflow-x-auto w-full">
    <table class="min-w-full divide-y-2 divide-zinc-200 bg-white text-sm">
        <thead class="ltr:text-left rtl:text-right">
        <tr>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                Nom de l'entreprise
            </th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                Nom de l'offre
            </th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                Email étudiant
            </th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                Dates de candidature
            </th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                Etat de la candidature
            </th>
        </tr>
        </thead>

        <tbody class="divide-y divide-zinc-200">
        <?php
        foreach ($candidatures as $candidature) {
            if($etatCandidature==="En attente") {
                if ($candidature["etatcandidature"] != "En attente") {
                    continue;
                }
            }
            if($etatCandidature!="En attente") {
                if ($candidature["etatcandidature"] === "En attente") {
                    continue;
                }
            }
            $offreIndividuelle = $offresRepo->getById($candidature["idoffre"]);
            $etudiant= new UtilisateurRepository();
            $etudiant = $etudiant->getUserById($candidature["idutilisateur"]);
            $entreprise = new EntrepriseRepository();
            $entreprise= $entreprise->getByIdFull($offreIndividuelle->getIdutilisateur());
            ?>
            <tr class="odd:bg-zinc-50">
                <td class="whitespace-nowrap px-4 py-2 font-medium text-zinc-900">
                    <?= $entreprise->getNomutilisateur(); ?>
                </td>
                <td class="whitespace-nowrap px-4 py-2 font-medium text-zinc-900">
                    <?php echo $offreIndividuelle->getSujet(); ?>
                </td>
                <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                    <?php
                    echo $etudiant->getEmailutilisateur();
                    ?>
                </td>
                <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                    <?php
                    echo $candidature["datec"];
                    ?>
                </td>
                <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                    <?php
                    echo $candidature["etatcandidature"];
                    ?>
                </td>
                <td class="whitespace-nowrap px-4 py-2">
                    <a href="/candidatures/<?php echo $candidature["idcandidature"] ?>"
                       class="inline-block rounded bg-zinc-600 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700">Voir
                        plus</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>

    </table>
</div>