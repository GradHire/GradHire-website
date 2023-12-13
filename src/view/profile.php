<?php
/** @var $user \app\src\model\repository\UtilisateurRepository */

/** @var $form FormModel */

use app\src\core\components\layout\Modal;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\Form\FormModel;

$this->title = 'Profile';

$modal = new Modal("Voulez vous vraiment archiver votre compte ?", "Oui, archiver", '
 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor"
                 class="text-zinc-400 dark:text-zinc-500 w-11 h-11 mb-3.5 mx-auto">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
            </svg>');
?>


<div class="mx-auto gap-4 mx-auto w-full lg:flex lg:gap-x-16 mx-auto max-w-md">
    <main class="px-4 sm:px-6 lg:flex-auto lg:px-0">
        <div class="mx-auto max-w-2xl space-y-16 sm:space-y-20 lg:mx-0 lg:max-w-none">
            <?php
            if (!is_null($form)) {
                ?>
                <?php $form->start(); ?>
                <div class="w-full gap-4 flex flex-col mx-auto max-w-md">

                    <img src="<?= $user->get_picture() ?>" alt="Photo de profil"
                         class="h-14 w-14 object-cover rounded-full" id="preview">
                    <?php
                    $form->print_all_fields();
                    $form->submit("Enregistrer les modifications");
                    $form->getError();
                    ?>
                </div>
            <?php $form->end(); ?>
                <script>
                    const imageInput = document.getElementById("image");
                    const imagePreview = document.getElementById("preview");

                    imageInput.addEventListener("change", function () {
                        const file = this.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function (e) {
                                imagePreview.src = e.target.result;
                                imagePreview.classList.remove("hidden");
                            };
                            reader.readAsDataURL(file);
                        } else {
                            imagePreview.src = "";
                            imagePreview.classList.add("hidden");
                        }
                    });
                </script>
            <?php
            } else { ?>
                <div>
                    <div class="flex flex-row gap-4">
                        <img src="<?= $user->get_picture() ?>" alt="Photo de profil"
                             class="h-14 w-14 object-cover rounded-full">
                        <div>
                            <h2 class="text-base font-semibold leading-7 text-zinc-900">Profile</h2>
                            <p class="mt-1 text-sm leading-6 text-zinc-500">Ces informations seront partagées avec les
                                autres utilisateurs.</p>
                        </div>
                    </div>
                    <div class="mt-6 space-y-6 divide-y divide-zinc-100 border-t border-zinc-200 text-sm leading-6">
                        <div class="pt-6 sm:flex">
                            <p class="font-medium text-zinc-900 sm:w-64 sm:flex-none sm:pr-6">Nom</p>
                            <div class="mt-1 flex justify-between gap-x-6 sm:mt-0 sm:flex-auto">
                                <div class="text-zinc-900"><?= $user->full_name() ?></div>
                            </div>
                        </div>
                        <?php if ($user->is_me()) { ?>
                            <div class="pt-6 sm:flex">
                                <p class="font-medium text-zinc-900 sm:w-64 sm:flex-none sm:pr-6">Role</p>
                                <div class="mt-1 flex justify-between gap-x-6 sm:mt-0 sm:flex-auto">
                                    <div class="text-zinc-900">
                                <span
                                        class="whitespace-nowrap rounded-full bg-zinc-100 px-2.5 py-0.5 text-center flex justify-center items-center text-xs text-zinc-600">
                                    <?= $user->role()->value ?>
                                    </span></div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php switch ($user->role()) {
                            case Roles::Student:
                                ?>
                                <div class="pt-6 sm:flex">
                                    <p class="font-medium text-zinc-900 sm:w-64 sm:flex-none sm:pr-6">Année</p>
                                    <div class="mt-1 flex justify-between gap-x-6 sm:mt-0 sm:flex-auto">
                                        <div class="text-zinc-900">BUT<?= $user->attributes()["annee"] ?></div>
                                    </div>
                                </div>
                                <?php
                                break;
                        } ?>
                        <div class="pt-6 sm:flex">
                            <p class="font-medium text-zinc-900 sm:w-64 sm:flex-none sm:pr-6">Bio</p>
                            <div class="mt-1 flex justify-between gap-x-6 sm:mt-0 sm:flex-auto">
                                <div class="text-zinc-900"><?php if ($user->attributes()["bio"] == "") {
                                        echo "Aucune bio";
                                    } else {
                                        echo $user->attributes()["bio"];
                                    } ?></div>
                            </div>
                        </div>
                        <div class="pt-6 flex gap-2 text-zinc-900">
                            <?php
                            if (Auth::has_role(Roles::Enterprise, Roles::Tutor)) {
                                ?>
                                <a href="/password"
                                   class="inline-block rounded-md border border-transparent px-5 py-2 text-center font-medium text-white hover:bg-gray-500 bg-gray-400 text-sm">
                                    Changer mot de passe</a>
                            <?php } ?>
                            <p <?= $modal->Show("/utilisateurs/" . $user->id() . "/archiver") ?>
                                    class="cursor-pointer inline-block rounded-md border border-transparent px-5 py-2 text-center font-medium text-white hover:bg-red-800 bg-red-600 text-sm">
                                Archiver
                                mon compte</p>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
</div>
</div>
