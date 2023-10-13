<?php
/** @var $utilisateur \app\src\model\dataObject\Staff */

use app\src\model\Application;
use app\src\model\repository\UtilisateurRepository;
?>

<div class="w-full">
    <div class="w-full flex md:flex-row flex-col justify-between items-start">
        <div class="px-4 sm:px-0">
            <h3 class="text-lg font-semibold leading-7 text-zinc-900"><?= $utilisateur->getNomutilisateur() ?></h3>
        </div>
        <div class="flex flex-row gap-4">
            <?php
            echo "<span class=\"inline-flex cursor-pointer  -space-x-px overflow-hidden rounded-md border bg-white shadow-sm\">";
            echo("<a href=\"/edit_profile/" . $utilisateur->getIdutilisateur() . "?redirect=". urlencode(Application::getRedirect())."\" class=\"cursor-pointer inline-block px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 focus:relative\">
                <svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"none\" viewBox=\"0 0 24 24\" stroke-width=\"1.5\" stroke=\"currentColor\" class=\"w-5 h-5\">
                <path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10\"/>
                </svg>
            </a>");
            if ((new UtilisateurRepository())->isArchived($utilisateur)) {
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
    <dl class="divide-y divide-zinc-100">
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt class="text-sm font-medium leading-6 text-zinc-900">Nom d'utilisateur</dt>
            <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0" id="nom">
                <?php
                $nom = $utilisateur->getNomutilisateur();
                if ($nom != null) echo $nom;
                else echo("Non renseigné");
                ?></dd>
        </div>
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt class="text-sm font-medium leading-6 text-zinc-900">Email</dt>
            <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0" id="email">
                <?php
                $email = $utilisateur->getEmailutilisateur();
                if ($email != null) echo $email;
                else echo("Non renseigné");
                ?></dd>
        </div>
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt class="text-sm font-medium leading-6 text-zinc-900">Numéro de téléphone</dt>
            <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0" id="tel">
                <?php
                $tel = $utilisateur->getNumtelutilisateur();
                if ($tel != null) echo $tel;
                else echo("Non renseigné");
                ?></dd>
        </div>
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt class="text-sm font-medium leading-6 text-zinc-900">Biographie </dt>
            <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0" id="tel">
                <?php
                $bio = $utilisateur->getBio();
                if ($bio != null) echo $bio;
                else echo("Non renseigné");
                ?></dd>
        </div>
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt class="text-sm font-medium leading-6 text-zinc-900">Prénom Tuteur </dt>
            <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0" id="tel">
                <?php
                $prenom = $utilisateur->getPrenomUtilisateurLDAP();
                if ($prenom != null) echo $prenom;
                else echo("Non renseigné");
                ?></dd>
        </div>
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt class="text-sm font-medium leading-6 text-zinc-900">Login LDAP </dt>
            <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0" id="tel">
                <?php
                $loginldap = $utilisateur->getLoginLDAP();
                if ($loginldap != null) echo $loginldap;
                else echo("Non renseigné");
                ?></dd>
        </div>
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt class="text-sm font-medium leading-6 text-zinc-900">Rôle </dt>
            <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0" id="tel">
                <?php
                $role = $utilisateur->getRole();
                if ($role != null) echo $role;
                else echo("Non renseigné");
                ?></dd>
        </div>
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt class="text-sm font-medium leading-6 text-zinc-900">Mail Universitaire </dt>
            <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0" id="tel">
                <?php
                $mailUni = $utilisateur->getMailuni();
                if ($mailUni != null) echo $mailUni;
                else echo("Non renseigné");
                ?></dd>
        </div>
    </dl>
</div>


