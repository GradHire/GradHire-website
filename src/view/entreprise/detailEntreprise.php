<?php

/** @var $entreprise \app\src\model\dataObject\Entreprise
 * @var $offres \app\src\model\dataObject\Offre
 */


?>
<div class="w-full">
    <div class="w-full flex md:flex-row flex-col justify-between items-start">
        <div class="px-4 sm:px-0">
            <h3 class="text-lg font-semibold leading-7 text-gray-900"><?= $entreprise->getNomutilisateur() ?></h3>
            <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500"><?= $entreprise->getTypestructure() ?></p>
        </div>
        <div>
            <?php
            if ($entreprise->getValidee() == "0") {
                echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-yellow-100 text-yellow-800\">
    En attente
    </span>";
            } else if ($entreprise->getValidee() == "1") {
                echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-green-100 text-green-800\">
    Validée
    </span>";
            }
            ?>
        </div>
    </div>
    <div class="mt-6 border-t border-gray-100">
        <dl class="divide-y divide-gray-100">
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Effectif</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                    <?php
                    $effectif = $entreprise->getEffectif();
                    if ($effectif != null) echo $effectif;
                    else echo("Non renseigné");
                    ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Code NAF</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                    <?php
                    $codeNaf = $entreprise->getCodenaf();
                    if ($codeNaf != null) echo $codeNaf;
                    else echo("Non renseigné");
                    ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Fax</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                    <?php
                    $fax = $entreprise->getFax();
                    if ($fax != null) echo $fax;
                    else echo("Non renseigné");
                    ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Site web</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                    <?php
                    $siteWeb = $entreprise->getSiteweb();
                    if ($siteWeb != null) echo "<a target=\"_blank\" href=\"" . $siteWeb . "\">" . $siteWeb . "</a>";
                    else echo("Non renseigné");
                    ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Numéro de téléphone</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                    <?php
                    $numTel = $entreprise->getNumtelutilisateur();
                    if ($numTel != null) echo $numTel;
                    else echo("Non renseigné");
                    ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Email</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                    <?php
                    $email = $entreprise->getEmailutilisateur();
                    if ($email != null) echo "<a href=\"mailto:" . $email . "\">" . $email . "</a>";
                    else echo("Non renseigné");
                    ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">SIRET</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                    <?php
                    $siret = $entreprise->getSiret();
                    if ($siret != null) echo $siret;
                    else echo("Non renseigné");
                    ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Statut juridique</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                    <?php
                    $statutJuridique = $entreprise->getStatutjuridique();
                    if ($statutJuridique != null) echo $statutJuridique;
                    else echo("Non renseigné");
                    ?></dd>
            </div>
            <div class="px-4 py-6 sm:gap-4 sm:px-0">
                <div class="w-full">
                    <table class="w-full divide-y-2 divide-gray-200 bg-white text-sm">
                        <thead class="ltr:text-left rtl:text-right">
                        <tr>
                            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-gray-900">
                                Sujet
                            </th>
                            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-gray-900">
                                Thématique
                            </th>
                            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-gray-900">
                                Date de création
                            </th>
                            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-gray-900">
                                Statut
                            </th>
                        </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                        <?php foreach ($offres as $offre) { ?>
                            <tr class="odd:bg-gray-50">
                                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                    <?= $offre['sujet']; ?>
                                </td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700">
                                    <?php
                                    $thematique = $offre['thematique'];
                                    if ($thematique != null) echo $thematique;
                                    else echo("Non renseigné");
                                    ?>
                                </td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700">
                                    <?php
                                    $dateCreation = new DateTime($offre['datecreation']);
                                    $dateCreation = $dateCreation->format('d/m/Y H:i:s');
                                    echo $dateCreation;
                                    ?>
                                </td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700">
                                    <?php
                                    if ($offre['status'] == "pending") {
                                        echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-yellow-100 text-yellow-800\">
    En attente
</span>";
                                    } else if ($offre['status'] == "approved") {
                                        echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-green-100 text-green-800\">
    Validée
    </span>";
                                    } else if ($offre['status'] == "blocked") {
                                        echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-red-100 text-red-800\">
    Refusée
    </span>";
                                    } else if ($offre['status'] == "draft") {
                                        echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-gray-100 text-gray-800\">
    Archivée
    </span>";
                                    }
                                    ?>
                                </td>
                                <td class="whitespace-nowrap px-4 py-2">
                                    <a href="/offres/<?= $offre['idoffre']; ?>"
                                       class="inline-block rounded bg-zinc-600 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700">Voir
                                        plus</a>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </dl>
    </div>
</div>
