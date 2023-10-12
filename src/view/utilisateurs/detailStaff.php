<?php
/** @var $utilisateur \app\src\model\dataObject\Staff */
?>

<div class="w-full">
    <div class="w-full flex md:flex-row flex-col justify-between items-start">
        <div class="px-4 sm:px-0">
            <h3 class="text-lg font-semibold leading-7 text-gray-900"><?= $utilisateur->getNomutilisateur() ?></h3>
        </div>
        <div class="flex flex-row gap-4">
            <a class="inline-block rounded bg-orange-400 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700"
               href="/edit_profile/<?= $utilisateur->getIdutilisateur() ?>">Edit</a>
            <a href="/archiver/<?= $utilisateur->getIdutilisateur() ?>"
               class="inline-block rounded bg-red-600 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700">Archiver</a>
        </div>
    </div>
    <dl class="divide-y divide-gray-100">
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt class="text-sm font-medium leading-6 text-gray-900">Nom d'utilisateur</dt>
            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0" id="nom">
                <?php
                $nom = $utilisateur->getNomutilisateur();
                if ($nom != null) echo $nom;
                else echo("Non renseigné");
                ?></dd>
        </div>
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt class="text-sm font-medium leading-6 text-gray-900">Email</dt>
            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0" id="email">
                <?php
                $email = $utilisateur->getEmailutilisateur();
                if ($email != null) echo $email;
                else echo("Non renseigné");
                ?></dd>
        </div>
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt class="text-sm font-medium leading-6 text-gray-900">Numéro de téléphone</dt>
            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0" id="tel">
                <?php
                $tel = $utilisateur->getNumtelutilisateur();
                if ($tel != null) echo $tel;
                else echo("Non renseigné");
                ?></dd>
        </div>
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt class="text-sm font-medium leading-6 text-gray-900">Biographie </dt>
            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0" id="tel">
                <?php
                $bio = $utilisateur->getBio();
                if ($bio != null) echo $bio;
                else echo("Non renseigné");
                ?></dd>
        </div>
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt class="text-sm font-medium leading-6 text-gray-900">Prénom Tuteur </dt>
            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0" id="tel">
                <?php
                $prenom = $utilisateur->getPrenomUtilisateurLDAP();
                if ($prenom != null) echo $prenom;
                else echo("Non renseigné");
                ?></dd>
        </div>
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt class="text-sm font-medium leading-6 text-gray-900">Login LDAP </dt>
            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0" id="tel">
                <?php
                $loginldap = $utilisateur->getLoginLDAP();
                if ($loginldap != null) echo $loginldap;
                else echo("Non renseigné");
                ?></dd>
        </div>
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt class="text-sm font-medium leading-6 text-gray-900">Rôle </dt>
            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0" id="tel">
                <?php
                $role = $utilisateur->getRole();
                if ($role != null) echo $role;
                else echo("Non renseigné");
                ?></dd>
        </div>
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt class="text-sm font-medium leading-6 text-gray-900">Mail Universitaire </dt>
            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0" id="tel">
                <?php
                $mailUni = $utilisateur->getMailuni();
                if ($mailUni != null) echo $mailUni;
                else echo("Non renseigné");
                ?></dd>
        </div>
    </dl>
</div>


