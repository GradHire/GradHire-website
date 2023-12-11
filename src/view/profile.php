<?php
/** @var $user \app\src\model\repository\UtilisateurRepository */

/** @var $form FormModel */

use app\src\core\components\Modal;
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


<div class="mx-auto gap-4 mx-auto w-full lg:flex lg:gap-x-16">
    <aside class="flex overflow-x-auto border-b border-zinc-900/5 py-4 lg:block lg:w-64 lg:flex-none lg:border-0 lg:py-20">
        <nav class="flex-none px-4 sm:px-6 lg:px-0">
            <ul class="flex gap-x-3 gap-y-1 whitespace-nowrap lg:flex-col">

                <li>
                    <a href="<?= $user->role() === Roles::Enterprise ? "/entreprises/{$user->id()}" : "/profile" ?>"
                       class="<?= is_null($form) ? "bg-zinc-100" : '' ?> text-zinc-700 group flex gap-x-3 rounded-md py-2 pl-2 pr-3 text-sm leading-6 font-semibold hover:bg-zinc-200">
                        <svg class="h-6 w-6 shrink-0 text-zinc-700" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Profile
                    </a>
                </li>
                <?php if (Auth::has_role(Roles::Staff, Roles::Manager)) { ?>

                    <li>
                        <a href="gererSimulPstage"
                           class="text-zinc-700 hover:text-zinc-600 hover:bg-zinc-50 group flex gap-x-3 rounded-md py-2 pl-2 pr-3 text-sm leading-6 font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                            </svg>
                            Gérer les simulations Pstage
                        </a>
                    </li>
                <?php } ?>
                <?php if (Auth::has_role(Roles::Student)) { ?>
                    <li>
                        <a href="gererSimulPstage"
                           class="text-zinc-700 hover:text-zinc-600 hover:bg-zinc-50 group flex gap-x-3 rounded-md py-2 pl-2 pr-3 text-sm leading-6 font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5"
                                 stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                            </svg>
                            Gérer mes simulations Pstage
                        </a>
                    </li>
                <?php } ?>

                <?php if ($user->is_me()) { ?>
                    <li>
                        <a href="/edit_profile"
                           class="<?= !is_null($form) ? "bg-zinc-100" : '' ?> text-zinc-700 hover:bg-zinc-200 group flex gap-x-3 rounded-md py-2 pl-2 pr-3 text-sm leading-6 font-semibold">
                            <svg class="h-6 w-6 shrink-0 text-zinc-700 group-hover:text-zinc-600" fill="none"
                                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M7.864 4.243A7.5 7.5 0 0119.5 10.5c0 2.92-.556 5.709-1.568 8.268M5.742 6.364A7.465 7.465 0 004.5 10.5a7.464 7.464 0 01-1.15 3.993m1.989 3.559A11.209 11.209 0 008.25 10.5a3.75 3.75 0 117.5 0c0 .527-.021 1.049-.064 1.565M12 10.5a14.94 14.94 0 01-3.6 9.75m6.633-4.596a18.666 18.666 0 01-2.485 5.33"/>
                            </svg>
                            Modifier le profile
                        </a>
                    </li>
                    <?php if (!Auth::has_role(Roles::Student)) { ?>

                        <li>
                            <a href="dashboard"
                               class="text-zinc-700 hover:text-zinc-600 hover:bg-zinc-50 group flex gap-x-3 rounded-md py-2 pl-2 pr-3 text-sm leading-6 font-semibold">
                                <svg class="h-6 w-6 shrink-0 text-zinc-700 group-hover:text-zinc-600" fill="none"
                                     viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                                </svg>
                                Dashboard
                            </a>
                        </li>
                    <?php }
                    if (Auth::has_role(Roles::Enterprise)) {
                        ?>
                        <li>
                            <a href="/entreprises/<?php echo $user->id() ?>"
                               class="text-zinc-700 hover:text-zinc-600 hover:bg-zinc-50 group flex gap-x-3 rounded-md py-2 pl-2 pr-3 text-sm leading-6 font-semibold">
                                <svg class="h-6 w-6 shrink-0 text-zinc-700 group-hover:text-zinc-600" fill="none"
                                     viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                                </svg>
                                Information Entreprise
                            </a>
                        </li>
                    <?php }
                } else { ?>
                    <li>
                        <a href="mailto:<?= $user->attributes()["email"] ?>" target="_blank"
                           class="text-zinc-700 hover:text-zinc-600 hover:bg-zinc-50 group flex gap-x-3 rounded-md py-2 pl-2 pr-3 text-sm leading-6 font-semibold">
                            <svg class="h-6 w-6 shrink-0 text-zinc-400 group-hover:text-zinc-600" fill="none"
                                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                            </svg>
                            Envoyer un email
                        </a>
                    </li>
                <?php } ?>
                <li>
                    <a href="/logout"
                       class="text-zinc-700 group flex gap-x-3 rounded-md py-2 pl-2 pr-3 text-sm leading-6 font-semibold hover:bg-zinc-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="h-6 w-6 shrink-0 text-zinc-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                        </svg>
                        Se déconnecter
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <main class="px-4 py-16 sm:px-6 lg:flex-auto lg:px-0 lg:py-20">
        <div class="mx-auto max-w-2xl space-y-16 sm:space-y-20 lg:mx-0 lg:max-w-none">
            <?php
            if (!is_null($form)) {
                ?>
                <?php $form->start(); ?>
                <div class="w-full gap-4 flex flex-col">

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
    </main>
</div>
