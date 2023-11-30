<?php

namespace app\src\core\components;

use app\src\core\exception\ServerErrorException;

class FormModal
{
    private string $id;

    /**
     * @throws ServerErrorException
     */
    public function __construct(callable|string $body = null)
    {
        if ($body)
            $this->render($body);
    }

    public function render(callable|string $body)
    {
        echo '<script src="/resources/js/modal.js"></script>';
        try {
            $this->id = bin2hex(random_bytes(5));
            echo '
<div id="' . $this->id . '" tabindex="-1" aria-hidden="true"
class="fixed hidden z-50 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-md">
<div class="relative p-10 text-center bg-white rounded-lg border-2 border-zinc-100 dark:bg-zinc-800 sm:p-10">
<button type="button"
onclick="closeModal(\'' . $this->id . '\')"
class="close-modal-btn text-zinc-400 absolute top-2.5 right-2.5 bg-transparent hover:bg-zinc-200 hover:text-zinc-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-zinc-600 dark:hover:text-white"
data-modal-toggle="deleteModal">
<svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd"
d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
clip-rule="evenodd"></path>
</svg>
<span class="sr-only">Close modal</span>
</button>';
            if (gettype($body) === "string")
                echo $body;
            else
                $body();
            echo <<<HTML
        </div>
    </div>
HTML;
        } catch (\Exception) {
            throw new ServerErrorException();
        }
    }

    public function Show(): string
    {
        return "onclick='showModal(\"$this->id\", \"\");'";
    }
}