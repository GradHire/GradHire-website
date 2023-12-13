<?php
/** @var $utilisateur \app\src\model\dataObject\Entreprise */

use app\src\model\Application;
use app\src\model\repository\UtilisateurRepository;

?>

<div class="w-full gap-4 mx-auto">
    <div class="w-full flex md:flex-row flex-col justify-between items-start">
        <div class="px-4 sm:px-0">
            <h3 class="text-lg font-semibold leading-7 text-zinc-900"><?= $utilisateur->getNom() ?></h3>
        </div>
        <div class="flex flex-row gap-4">
            <?php
            echo "<span class=\"inline-flex cursor-pointer  -space-x-px overflow-hidden rounded-md border bg-white shadow-sm\">";
            echo("<a href=\"/edit_profile/" . $utilisateur->getIdutilisateur() . "?" . Application::getRedirect() . "\" class=\"cursor-pointer inline-block px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 focus:relative\">
                <svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"none\" viewBox=\"0 0 24 24\" stroke-width=\"1.5\" stroke=\"currentColor\" class=\"w-5 h-5\">
                <path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10\"/>
                </svg>
            </a>");
            if ((new UtilisateurRepository([]))->isArchived($utilisateur)) {
                echo("<a href=\"/utilisateurs/" . $utilisateur->getIdutilisateur() . "/archiver\" class=\"inline-block px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 focus:relative\">
                    <svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"none\" viewBox=\"0 0 24 24\" stroke-width=\"1.5\" stroke=\"currentColor\" class=\"w-5 h-5\">
                    <path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z\"/>
                    </svg>
                </a>");
            } else {
                echo("<a href=\"/utilisateurs/" . $utilisateur->getIdutilisateur() . "/archiver\"
                        class=\"inline-block px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 focus:relative\">
                    <svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"none\" viewBox=\"0 0 24 24\" stroke-width=\"1.5\" stroke=\"currentColor\" class=\"w-5 h-5\">
                    <path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z\"/>
                    </svg>
                    </a>");
            }
            echo "</span>";
            ?>
        </div>
    </div>
    <div class="mt-6 border-t border-zinc-100">
        <dl class="divide-y divide-zinc-100">
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">Effectif</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
                    <?php
                    $effectif = $utilisateur->getEffectif();
                    if ($effectif != null) echo $effectif;
                    else echo("Non renseigné");
                    ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">Code NAF</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
                    <?php
                    $codeNaf = $utilisateur->getCodenaf();
                    if ($codeNaf != null) echo $codeNaf;
                    else echo("Non renseigné");
                    ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">Fax</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
                    <?php
                    $fax = $utilisateur->getFax();
                    if ($fax != null) echo $fax;
                    else echo("Non renseigné");
                    ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">Site web</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
                    <?php
                    $siteWeb = $utilisateur->getSiteweb();
                    if ($siteWeb != null) echo "<a target=\"_blank\" href=\"" . $siteWeb . "\">" . $siteWeb . "</a>";
                    else echo("Non renseigné");
                    ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">Numéro de téléphone</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
                    <?php
                    $numTel = $utilisateur->getNumtelephone();
                    if ($numTel != null) echo $numTel;
                    else echo("Non renseigné");
                    ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">Email</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
                    <?php
                    $email = $utilisateur->getEmail();
                    if ($email != null) echo "<a href=\"mailto:" . $email . "\">" . $email . "</a>";
                    else echo("Non renseigné");
                    ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">SIRET</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
                    <?php
                    $siret = $utilisateur->getSiret();
                    if ($siret != null) echo $siret;
                    else echo("Non renseigné");
                    ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">Statut juridique</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
                    <?php
                    $statutJuridique = $utilisateur->getStatutjuridique();
                    if ($statutJuridique != null) echo $statutJuridique;
                    else echo("Non renseigné");
                    ?></dd>
            </div>
        </dl>
    </div>

