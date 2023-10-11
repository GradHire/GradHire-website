<?php
/** @var $utilisateur \app\src\model\dataObject\Utilisateur */
?>
<div class="w-full">
    <div class="w-full flex md:flex-row flex-col justify-between items-start">
        <div class="px-4 sm:px-0">
            <h3 class="text-lg font-semibold leading-7 text-gray-900"><?= $utilisateur->getNomutilisateur() ?></h3>
        </div>
        <div class="flex flex-row gap-4">
            <form method="POST" name="save" action="/edit_profile/<?= $utilisateur->getIdutilisateur() ?>"
                  class="mt-6 border-t border-gray-100 w-full" id="detailUtilisateur">
                <td class="whitespace-nowrap px-4 py-2">
                    <button type="submit" value="edit" name="edit" id="buttunEdit"
                            class="inline-block rounded bg-orange-400 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700">
                        Edit
                    </button>
                </td>
            </form>
            <form method="POST" name="archiver" action="/archiver/<?= $utilisateur->getIdutilisateur() ?>"
                  class="mt-6 border-t border-gray-100 w-full" id="detailUtilisateur">
                <td class="whitespace-nowrap px-4 py-2">
                    <button type="submit" value="archiver" name="archiver"
                            class="inline-block rounded bg-red-600 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700">Archiver</button>
                </td>
            </form>
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
    </dl>
</div>


