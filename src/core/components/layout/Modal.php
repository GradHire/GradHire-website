<?php

namespace app\src\core\components\layout;

use app\src\core\exception\ServerErrorException;

class Modal
{
    private string $id;

    /**
     * @throws ServerErrorException
     */
    public function __construct(string $body, string $btn, string $svg = "")
    {
        echo '<script src="/resources/js/modal.js"></script>';
        try {
            $this->id = bin2hex(random_bytes(5));
            echo <<<HTML
    <div id="$this->id" tabindex="-1" aria-hidden="true"
         class="fixed hidden z-50 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-md">
        <div class="relative p-10 text-center bg-white rounded-lg border dark:bg-zinc-800 sm:p-10 shadow-xl">
            <button type="button"
                    onclick="closeModal('$this->id')"
                    class="close-modal-btn text-zinc-400 absolute top-2.5 right-2.5 bg-transparent hover:bg-zinc-200 hover:text-zinc-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-zinc-600 dark:hover:text-white"
                    data-modal-toggle="deleteModal">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                     xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                          d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                          clip-rule="evenodd"></path>
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            $svg
HTML;
            echo "<p class=\"mb-4 text-zinc-500 dark:text-zinc-300\">$body</p>";
            echo <<<HTML
            <div class="flex justify-center items-center space-x-4">
                <button data-modal-toggle="deleteModal" type="button"
                        onclick="closeModal('$this->id')"
                        class="close-modal-btn py-2 px-3 text-sm font-medium text-zinc-500 bg-white rounded-lg border border-zinc-200 hover:bg-zinc-100 focus:ring-4 focus:outline-none focus:ring-primary-300 hover:text-zinc-900 focus:z-10 dark:bg-zinc-700 dark:text-zinc-300 dark:border-zinc-500 dark:hover:text-white dark:hover:bg-zinc-600 dark:focus:ring-zinc-600">
                    Annuler
                </button>

                <div class="m-0" id="modal-form">
                    <input type="hidden" name="link" value="" id="modal-redirect">
                    <input type="hidden" name="delete" value="" id="modal-delete">
                    <a class="action-btn close-modal-btn  py-2 px-3 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                        $btn
                    </a>
                </div>
            </div>
        </div>
    </div>
HTML;
        } catch (\Exception) {
            throw new ServerErrorException();
        }
    }

    public function Show(string $action): string
    {
        return "onclick='showModal(\"$this->id\", \"$action\");'";
    }
}