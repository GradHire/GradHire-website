<?php

/** @var $convention \app\src\model\dataObject\Convention */

use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\repository\ConventionRepository;
use app\src\model\repository\EntrepriseRepository;
use app\src\model\repository\OffresRepository;

if (Auth::has_role(Roles::Enterprise) && Auth::get_user()->id() != (new OffresRepository())->getById($convention->getIdOffre())->getIdutilisateur()) {
    throw new Exception("Vous n'avez pas le droit d'accéder à cette convention car elle n'appartient pas à votre entreprise.");
}
?>

<div class="w-full pt-12 pb-24">
    <div class="w-full flex md:flex-row flex-col justify-between items-start">
        <div class="px-4 sm:px-0">
            <h3 class="text-lg font-semibold leading-7 text-zinc-900">Convention numero
                : <?= $convention->getNumConvention() ?></h3>
            <p class="mt-1 max-w-2xl text-sm leading-6 text-zinc-500">
                <?php
                try {
                    $date = new DateTime($convention->getDateCreation());
                    echo "Publiée le " . $date->format('d/m/Y') . " à " . $date->format('H:i:s');
                } catch (Exception $e) {
                    echo "Something went wrong.";
                }
                ?></p>
        </div>
    </div>
    <div class="mt-6 border-t border-zinc-100">
        <dl class="divide-y divide-zinc-100">
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">Validiter Convention</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
                    <div>
                        <?php
                        if ($convention->getConventionValide() == "0") {
                            echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-zinc-100 text-zinc-800\">
Non valide
    </span>";
                        } else if ($convention->getConventionValide() == "1") {
                            echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-green-100 text-green-800\">
    Validée
    </span>";
                        }
                        ?>
                    </div>
                </dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">Validiter Pedagogique</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
                    <div>
                        <?php
                        if ($convention->getConvetionValideePedagogiquement() == "0") {
                            echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-zinc-100 text-zinc-800\">
    Non valide
    </span>";
                        } else if ($convention->getConvetionValideePedagogiquement() == "1") {
                            echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-green-100 text-green-800\">
    Validée
    </span>";
                        }
                        ?>
                    </div>
                </dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">Origine Convention</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0"><?= $convention->getOrigineConvention() ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">Date Modification</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0"><?= $convention->getDateModification() ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">Date Creation</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0"><?= $convention->getDateCreation() ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">idSignatiare</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0"><?= $convention->getIdSignataire() ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">idIterruption</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0"><?php
                    $idIterruption = $convention->getIdInterruption();
                    if ($idIterruption != null) echo $idIterruption;
                    else echo("Non renseigné");
                    ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">Etudiant</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0"><a
                            href="/entreprises/<?= $convention->getIdUtilisateur() ?>"><?= (new EntrepriseRepository([]))->getUserById($convention->getIdUtilisateur())->getNomutilisateur() ?></a>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">idOffre</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0"><?= $convention->getIdOffre() ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">Commentaire</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0"><?php
                    $commentaire = $convention->getCommentaire();
                    if ($commentaire != null) echo $commentaire;
                    else echo("Non renseigné");
                    ?></dd>
            </div>
        </dl>
    </div>
</div>
