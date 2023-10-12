<?php
/** @var $offre \app\src\model\dataObject\Offre
 * @var $utilisateurs array
 * @var $currentFilterURL string
 */

use app\src\model\Application;

$id_offre = $offre->getIdoffre();

?>
<div id="myModal-<?= $id_offre ?>" tabindex="-1" aria-hidden="true" class="fixed hidden z-50 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-md">
    <div class="relative p-10 text-center bg-white rounded-lg border-2 border-zinc-100 dark:bg-zinc-800 sm:p-10">
        <button type="button"
                class="close-modal-btn-<?= $id_offre ?> text-zinc-400 absolute top-2.5 right-2.5 bg-transparent hover:bg-zinc-200 hover:text-zinc-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-zinc-600 dark:hover:text-white"
                data-modal-toggle="deleteModal">
            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                 xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                      d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                      clip-rule="evenodd"></path>
            </svg>
            <span class="sr-only">Close modal</span>
        </button>
        <svg class="text-zinc-400 dark:text-zinc-500 w-11 h-11 mb-3.5 mx-auto" aria-hidden="true" fill="currentColor"
             viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
                  d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                  clip-rule="evenodd"></path>
        </svg>
        <p class="mb-4 text-zinc-500 dark:text-zinc-300">Êtes-vous sûr de vouloir supprimer cette offre ?</p>
        <div class="flex justify-center items-center space-x-4">
            <button data-modal-toggle="deleteModal" type="button"
                    class="close-modal-btn-<?= $id_offre ?> py-2 px-3 text-sm font-medium text-zinc-500 bg-white rounded-lg border border-zinc-200 hover:bg-zinc-100 focus:ring-4 focus:outline-none focus:ring-primary-300 hover:text-zinc-900 focus:z-10 dark:bg-zinc-700 dark:text-zinc-300 dark:border-zinc-500 dark:hover:text-white dark:hover:bg-zinc-600 dark:focus:ring-zinc-600">
                Annuler
            </button>
            <form class="m-0" method="POST" action="/offres/<?= $id_offre ?>/delete">
                <input type="hidden" name="link" value="<?= $currentFilterURL; ?>">
                <input type="hidden" name="delete" value="<?= $id_offre ?>">
                <button type="submit" value="Delete"
                        class="close-modal-btn-<?= $id_offre ?>  py-2 px-3 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    Oui, supprimer
                </button>
            </form>
        </div>
    </div>
</div>
<div class="relative offreBox">
    <div class="absolute top-0 right-0 flex flex-row gap-1 mt-11 mr-4 z-10">
        <form class="formAdminSupprimer flex items-center justify-center w-7 h-7 border-[1px] border-zinc-100 duration-150 bg-zinc-50 hover:invert rounded-full"
              method="POST" action="/offres/<?= $id_offre ?>/edit">
            <input type="hidden" name="edit" value="<?= $id_offre ?>">
            <button type="submit" value="Edit" class="btn btn-danger">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                </svg>

            </button>
        </form>
        <button id="btn-danger-delete-<?= $id_offre ?>"
                class="formAdminSupprimer  flex items-center justify-center w-7 h-7 border-[1px] border-zinc-100 duration-150 bg-zinc-50 hover:invert  rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
            </svg>
        </button>
    </div>
    <div class=" rounded-[10px] relative cursor-pointer group bg-white p-4 shadow-sm hover:shadow min-w-[200px] shrink duration-150 border-2 border-zinc-200 hover:border-zinc-300">
        <a href="/offres/<?= $id_offre ?>">
            <div class="w-full flex flex-row justify-between items-center">
    <span class="whitespace-nowrap rounded-md bg-zinc-100 px-2.5 py-0.5 text-center flex justify-center items-center text-xs text-zinc-600">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
             class="w-3 h-3 mr-0.5">
  <path stroke-linecap="round" stroke-linejoin="round"
        d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5l-3.9 19.5m-2.1-19.5l-3.9 19.5"/>
</svg>

        <?= ucfirst($offre->getThematique()) ?></span>
                <?php $dateCreation = new DateTime($offre->getDatecreation());
                $dateCreation = $dateCreation->format('d/m/Y');
                echo "<p class=\"block text-xs text-zinc-500\">" . $dateCreation . "</p>";
                ?>
            </div>
            <div class=" text-lg font-bold mt-2 text-zinc-900">
                <p><?= ucfirst($offre->getSujet()) ?></p>
            </div>
            <div>
                <p class="text-sm text-zinc-500">
                    <?php
                    //if description is too long (more than 50 characters), we cut it and add "..."
                    if (strlen($offre->getDescription()) > 50) echo substr(ucfirst($offre->getDescription()), 0, 50) . "…";
                    else echo ucfirst($offre->getDescription()) ?>
                </p>
            </div>
            <div class="mt-4 flex w-full gap-1">
                <div class="flex flex-row gap-1 items-center">
                    <img class="w-5 h-5 rounded-full" src="<?= Application::getUser()->get_picture() ?>"
                         alt="Jese Leos avatar"/>
                    <span class="whitespace-nowrap px-1 py-0.5 font-medium text-center flex justify-center items-center text-xs text-zinc-400">
            <?php
            $userId = $offre->getIdutilisateur();
            if (isset($utilisateurs[$userId])) echo $utilisateurs[$userId];
            else echo 'Inconnu';
            ?>
        </span>
                </div>
                <span class="group w-full pr-2 group-hover:pr-0 duration-150 inline-flex items-end justify-end gap-1 text-sm font-medium text-zinc-600"><span
                            aria-hidden="true"
                            class="block transition-all group-hover:ms-0.5 rtl:rotate-180">&rarr;</span></span>
            </div>
        </a>
    </div>
</div>
<script>
    const modal<?= $id_offre ?>= document.getElementById("myModal-<?= $id_offre ?>");
    const bg<?= $id_offre ?> = document.getElementById("blur-background")
    const btn<?= $id_offre ?> = document.getElementById("btn-danger-delete-<?= $id_offre ?>");
    const closeBtns<?= $id_offre ?> = document.querySelectorAll(".close-modal-btn-<?= $id_offre ?>");
    btn<?= $id_offre ?>.onclick = function () {
        modal<?= $id_offre ?>.classList.remove("hidden");
        modal<?= $id_offre ?>.classList.add("block");
        bg<?= $id_offre ?>.classList.remove("hidden");
    }
    closeBtns<?= $id_offre ?>.forEach(function (btn) {
        btn.onclick = function () {
            modal<?= $id_offre ?>.classList.add("hidden");
            modal<?= $id_offre ?>.classList.remove("block");
            bg<?= $id_offre ?>.classList.add("hidden");
        }
    });
</script>


