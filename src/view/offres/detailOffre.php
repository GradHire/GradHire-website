<?php

/** @var $offre \app\src\model\dataObject\Offre */

?>

<div class="w-full">
    <div class="w-full flex md:flex-row flex-col justify-between items-start">
        <div class="px-4 sm:px-0">
            <h3 class="text-lg font-semibold leading-7 text-gray-900"><?= $offre->getSujet() ?></h3>
            <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">
                <?php
                try {
                    $date = new DateTime($offre->getDatecreation());
                    echo "Publiée le " . $date->format('d/m/Y') . " à " . $date->format('H:i:s');
                } catch (Exception $e) {
                    echo "Something went wrong.";
                }
                ?></p>
        </div>
        <div>
            <?php
            if ($offre->getStatut() == "pending") {
                echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-yellow-100 text-yellow-800\">
    En attente
    </span>";
            } else if ($offre->getStatut() == "approved") {
                echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-green-100 text-green-800\">
    Validée
    </span>";
            } else if ($offre->getStatut() == "blocked") {
                echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-red-100 text-red-800\">
    Refusée
    </span>";
            } else if ($offre->getStatut() == "draft") {
                echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-gray-100 text-gray-800\">
    Archivée
    </span>";
            }
            ?>
        </div>
    </div>
    <div class="mt-6 border-t border-gray-100">
        <dl class="divide-y divide-gray-100">
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Thématique</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"><?= $offre->getThematique() ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Entreprise</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"><a
                            href="/entreprises/<?= $offre->getIdutilisateur() ?>"><?= $offre->getNomutilisateur() ?></a>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Durée</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"><?= $offre->getDuree() ?> an(s)
                </dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Dates</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"><?php
                    try {
                        $dateDebut = new DateTime($offre->getDatedebut());
                        $dateFin = new DateTime($offre->getDatefin());
                        if ($dateFin < new DateTime()) echo "Terminée";
                        else if (is_null($offre->getDatefin())) echo "Indéterminée";
                        else echo "Du " . $dateDebut->format('d/m/Y') . " au " . $dateFin->format('d/m/Y');
                    } catch (Exception $e) {
                        echo "Something went wrong.";
                    }
                    ?>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Année visée</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"><?= $offre->getAnneeVisee() ?>A
                </dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Gratification</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"><?= $offre->getGratification() ?>
                    €/<?= $offre->getUnitegratification() ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Description</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"><?= $offre->getDescription() ?></dd>
            </div>
        </dl>
    </div>
</div>
