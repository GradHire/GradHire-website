<?php

/** @var $offres \app\src\model\dataObject\Offre
 */

use app\src\model\Auth;
use app\src\model\dataObject\Roles;


?>

<div class="w-full pt-12 pb-24">
    <?php if ($offres != null) { ?>
        <div class="px-4 py-6 sm:gap-4 sm:px-0">
            <div class="w-full">
                <table class="w-full divide-y-2 divide-zinc-200 bg-white text-sm">
                    <thead class="ltr:text-left rtl:text-right">
                    <tr>
                        <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                            Sujet
                        </th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                            Thématique
                        </th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                            Date de création
                        </th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                            Statut
                        </th>
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-zinc-200">
                    <?php
                    if ($offres != null)
                        foreach ($offres as $offre) {
                            if(Auth::has_role(Roles::Tutor)){
                                if($offre->getStatut() != "approved"){
                                    continue;
                                }
                            }
                            ?>
                            <tr class="odd:bg-zinc-50">
                                <td class="whitespace-nowrap px-4 py-2 font-medium text-zinc-900">
                                    <?= $offre->getSujet() ?>
                                </td>
                                <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                                    <?php
                                    $thematique = $offre->getThematique();
                                    if ($thematique != null) echo $thematique;
                                    else echo("Non renseigné");
                                    ?>
                                </td>
                                <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                                    <?php
                                    $dateCreation = new DateTime($offre->getDateCreation());
                                    $dateCreation = $dateCreation->format('d/m/Y H:i:s');
                                    echo $dateCreation;
                                    ?>
                                </td>
                                <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                                    <?php
                                    $status = $offre->getStatut();

                                    if ($status == "pending") {
                                        echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-yellow-100 text-yellow-800\">
    En attente
</span>";
                                    } else if ($status == "approved") {
                                        echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-green-100 text-green-800\">
    Validée
    </span>";
                                    } else if ($status == "blocked") {
                                        echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-red-100 text-red-800\">
    Refusée
    </span>";
                                    } else if ($status == "draft") {
                                        echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-zinc-100 text-zinc-800\">
    Archivée
    </span>";
                                    }
                                    ?>
                                </td>
                                <td class="whitespace-nowrap px-4 py-2">
                                    <a href="/offres/<?= $offre->getId() ?>"
                                       class="inline-block rounded bg-zinc-600 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700">Voir
                                        plus</a>
                                </td>
                            </tr>
                            <?php
                        }
                    ?>
                    </tbody>

                </table>
            </div>
        </div>
    <?php } ?>
</div>

