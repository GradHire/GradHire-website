<?php
/** @var $id_offre string
 * @var $currentFilterURL string
 */
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
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
             class="text-zinc-400 dark:text-zinc-500 w-11 h-11 mb-3.5 mx-auto">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
        </svg>

        <p class="mb-4 text-zinc-500 dark:text-zinc-300">Êtes-vous sûr de vouloir archiver cette offre ?</p>
        <div class="flex justify-center items-center space-x-4">
            <button data-modal-toggle="deleteModal" type="button"
                    class="close-modal-btn-<?= $id_offre ?> py-2 px-3 text-sm font-medium text-zinc-500 bg-white rounded-lg border border-zinc-200 hover:bg-zinc-100 focus:ring-4 focus:outline-none focus:ring-primary-300 hover:text-zinc-900 focus:z-10 dark:bg-zinc-700 dark:text-zinc-300 dark:border-zinc-500 dark:hover:text-white dark:hover:bg-zinc-600 dark:focus:ring-zinc-600">
                Annuler
            </button>
            <form class="m-0" method="POST" action="/offres/<?= $id_offre ?>/archive">
                <input type="hidden" name="link" value="<?= $currentFilterURL; ?>">
                <input type="hidden" name="delete" value="<?= $id_offre ?>">
                <button type="submit" value="Delete"
                        class="close-modal-btn-<?= $id_offre ?>  py-2 px-3 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    Oui, Archivez
                </button>
            </form>
        </div>
    </div>
</div>