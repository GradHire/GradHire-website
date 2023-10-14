<?php
/** @var $candidatures \app\src\model\dataObject\Candidature */
/** @var $titre string */

use app\src\model\repository\EntrepriseRepository;
use app\src\model\repository\EtudiantRepository;
use app\src\model\repository\OffresRepository;
use app\src\model\repository\UtilisateurRepository;

?>
                        <div class="flex flex-col gap-1 w-full">
                <h2 class="font-bold text-lg"><?php echo $titre ?></h2>
<div class=" gap-4 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 grid-cols-1 content-start place-items-stretch justify-items-stretch">
    <div class="overflow-x-auto w-full">
        <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
            <thead class="ltr:text-left rtl:text-right">
            <tr>
                <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-gray-900">
                    Nom de l'entreprise
                </th>
                <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-gray-900">
                    Nom de l'offre
                </th>
                <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-gray-900">
                    Email étudiant
                </th>
                <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-gray-900">
                    Dates de candidature
                </th>
                <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-gray-900">
                    Etat de la candidature
                </th>
            </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
            <?php
            foreach ($candidatures as $candidature) {
                $entreprise=(new UtilisateurRepository())->getUserById($candidature->getIdutilisateur());
                $offre=(new OffresRepository())->getById($candidature->getIdoffre());
                $etudiant=(new UtilisateurRepository())->getUserById($candidature->getIdutilisateur());
                ?>
                <tr class="odd:bg-gray-50">
                    <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                        <?= $entreprise->getNomutilisateur(); ?>
                    </td>
                    <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                        <?php echo $offre->getSujet(); ?>
                    </td>
                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">
                        <?php
                        echo $etudiant->getEmailutilisateur();
                        ?>
                    </td>
                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">
                        <?php
                        echo $candidature->getDatecandidature();
                        ?>
                    </td>
                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">
                        <?php
                        if($candidature->getEtatcandidature()=='on hold') {
                            echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-yellow-100 text-yellow-800\">En attente</span>";
                        }
                        elseif ($candidature->getEtatcandidature()=='declined') {
                            echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-red-100 text-red-800\">Refusé</span>";
                        }
                        else echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-green-100 text-green-800\">Accepté</span>";

                        ?>
                    </td>
                    <td class="whitespace-nowrap px-4 py-2">
                        <a href="/candidatures/<?php echo $candidature->getIdcandidature() ?>"
                           class="inline-block rounded bg-zinc-600 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700">Voir
                            plus</a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>

        </table>

    </div>
</div>
</div>