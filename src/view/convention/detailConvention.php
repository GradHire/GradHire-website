<?php

/** @var $convention \app\src\model\dataObject\Convention */

use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\repository\ConventionRepository;
use app\src\model\repository\EntrepriseRepository;
use app\src\model\repository\OffresRepository;

if (Auth::has_role(Roles::Student, Roles::Enterprise) && !(new ConventionRepository())->checkIfConventionsValideePedagogiquement($convention->getNumConvention())) {
    throw new Exception("Vous n'avez pas le droit d'accéder à cette convention car elle n'a pas été validée pédagogiquement.");
} else if (Auth::has_role(Roles::Enterprise) && Auth::get_user()->id() != (new OffresRepository())->getById($convention->getIdOffre())->getIdutilisateur()) {
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
        <?php
        if (Auth::has_role(Roles::Staff, Roles::Manager) || Auth::get_user()->getId() == (new OffresRepository())->getById($convention->getIdOffre())->getIdutilisateur()) {
            ?>
            <span class="inline-flex cursor-pointer  -space-x-px overflow-hidden rounded-md border bg-white shadow-sm">
  <a href="/conventions/<?= $convention->getNumConvention() ?>/edit"
     class="cursor-pointer inline-block px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 focus:relative">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
         class="w-5 h-5">
  <path stroke-linecap="round" stroke-linejoin="round"
        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
</svg>
  </a>
                        <?php
                        if (Auth::has_role(Roles::Enterprise)) {
                            if ($convention->getConventionValide() != "1"): ?>
                            <a href="/conventions/<?= $convention->getNumConvention() ?>/validate"
                               class="inline-block px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 focus:relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5"
                                         stroke="currentColor"
                                         class="w-5 h-5">
  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
</svg>
                                </a><?php endif; ?>
                <?php if ($convention->getConventionValide() != "0"): ?>
                            <a href="/conventions/<?= $convention->getNumConvention() ?>/unvalidate"
                               class="cursor-pointer inline-block px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 focus:relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5"
                                         stroke="currentColor"
                                         class="w-5 h-5">
  <path stroke-linecap="round" stroke-linejoin="round"
        d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
</svg>
                                </a><?php endif;
                        } ?>
                <?php
                if (Auth::has_role(Roles::Staff, Roles::Manager)) {
                    if ($convention->getConvetionValideePedagogiquement() != "1"): ?>
                    <a href="/conventions/<?= $convention->getNumConvention() ?>/validatePedagogiquement"
                       class="inline-block px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 focus:relative">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor"
                                 class="w-5 h-5">
  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
</svg>
                        </a><?php endif; ?>
                <?php if ($convention->getConvetionValideePedagogiquement() != "0"): ?>
                    <a href="/conventions/<?= $convention->getNumConvention() ?>/unvalidatePedagogiquement"
                       class="cursor-pointer inline-block px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 focus:relative">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor"
                                 class="w-5 h-5">
  <path stroke-linecap="round" stroke-linejoin="round"
        d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
</svg>
                        </a><?php endif;
                } ?>
</span>
        <?php } ?>
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
