<?php
/** @var $utilisateur \app\src\model\dataObject\Entreprise */
use app\src\model\repository\UtilisateurRepository;
?>

<div class="w-full">
    <div class="w-full flex md:flex-row flex-col justify-between items-start">
        <div class="px-4 sm:px-0">
            <h3 class="text-lg font-semibold leading-7 text-zinc-900"><?= $utilisateur->getNomutilisateur() ?></h3>
        </div>
        <div class="flex flex-row gap-4">
            <a class="inline-block rounded bg-orange-400 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700" href="/edit_profile/<?= $utilisateur->getIdutilisateur() ?>">Edit</a>
            <?php
            if((new UtilisateurRepository())->isArchived($utilisateur)){
                echo("<a class=\"inline-block rounded bg-green-600 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700\" href=\"/utilisateurs/". $utilisateur->getIdutilisateur() ."/archiver\">Désarchiver</a>");
            } else {
                echo("<a class=\"inline-block rounded bg-red-600 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700\" href=\"/utilisateurs/". $utilisateur->getIdutilisateur() ."/archiver\">Archiver</a>");
            }
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
                    $numTel = $utilisateur->getNumtelutilisateur();
                    if ($numTel != null) echo $numTel;
                    else echo("Non renseigné");
                    ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">Email</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
                    <?php
                    $email = $utilisateur->getEmailutilisateur();
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

