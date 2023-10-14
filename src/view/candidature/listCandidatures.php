<?php
/** @var $candidatures \app\src\model\dataObject\Candidature */

use app\src\model\Auth\Auth;
use app\src\model\repository\EntrepriseRepository;
use app\src\model\repository\OffresRepository;
use app\src\model\repository\EtudiantRepository;
use app\src\model\repository\UtilisateurRepository;

?>

<form method="GET" action="offres" class="w-full gap-4 flex flex-col">
    <div class="w-full grid-cols-1 gap-4 lg:grid-cols-4 ">
        <div class="w-full lg:col-span-3 rounded-lg flex flex-col gap-4">
            <div class="flex flex-col gap-1 w-full">
                <h2 class="font-bold text-lg">Candidature en Attente</h2>
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

                                    if($candidature->getEtatcandidature()=='on hold'){
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
                                        if ($candidature->getEtatcandidature() == "on hold") {
                                            echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-yellow-100 text-yellow-800\">
    En attente
    </span>";
                                        } else if ($candidature->getEtatcandidature() == "accepted") {
                                            echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-green-100 text-green-800\">
    Acceptée
    </span>";
                                        } else if ($candidature->getEtatcandidature() == "declined") {
                                            echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-red-100 text-red-800\">
    Refusée
    </span>";
                                        } else if ($candidature->getEtatcandidature() == "draft") {
                                            echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-zinc-100 text-zinc-800\">
    Archivée
    </span>";
                                        }else{
                                            echo $candidature->getEtatcandidature();
                                        }
                                        ?>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-2">
                                        <a href="/candidatures/<?php echo $candidature->getIdcandidature() ?>"
                                           class="inline-block rounded bg-zinc-600 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700">Voir
                                            plus</a>
                                    </td>
                                </tr>
                            <?php }} ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
            <?php
                echo '<div class="w-full bg-zinc-200 h-[1px] rounded-full"></div>';
                echo '<div class="flex flex-col gap-1 w-full">';
                echo '<h2 class="font-bold text-lg">Candidatures déja Vue</h2>';
            ?>
            <div class="w-full">
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
                            if($candidature->getEtatcandidature()!='on hold'){
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
                                        if ($candidature->getEtatcandidature() == "on hold") {
                                            echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-yellow-100 text-yellow-800\">
    En attente
    </span>";
                                        } else if ($candidature->getEtatcandidature() == "accepted") {
                                            echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-green-100 text-green-800\">
    Acceptée
    </span>";
                                        } else if ($candidature->getEtatcandidature() == "declined") {
                                            echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-red-100 text-red-800\">
    Refusée
    </span>";
                                        } else if ($candidature->getEtatcandidature() == "draft") {
                                            echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-zinc-100 text-zinc-800\">
    Archivée
    </span>";
                                        }else{
                                            echo $candidature->getEtatcandidature();
                                        }
                                        ?>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-2">
                                        <a href="/candidatures/<?php echo $candidature->getIdcandidature(); ?>"
                                           class="inline-block rounded bg-zinc-600 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700">Voir
                                            plus</a>
                                    </td>
                                </tr>
                            <?php }} ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
</form>
