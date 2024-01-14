<?php

namespace app\src\view\components\layout;

use app\src\model\Application;
use app\src\view\components\ComponentInterface;
use app\src\view\resources\icons\I_Chat;
use app\src\view\resources\icons\I_Close;
use app\src\view\resources\icons\I_Send;
use InvalidArgumentException;

class Chatbot implements ComponentInterface
{
    /**
     * Cette méthode static permet de générer un rendu visuel en fonction du tableau de paramètres fournis.
     *
     * @param array $params Un tableau associatif de paramètres nécessaires pour le rendu.
     *
     * @return void
     *
     * @throws InvalidArgumentException si les paramètres fournis ne sont pas corrects.
     */
    public static function render(array $params): void
    {
        $i_close_render = I_Close::render('w-4 h-4');
        $user_full_name = Application::getUser()->full_name();
        $i_send_render = I_Send::render('w-4 h-4');
        $i_chat_render = I_Chat::render('w-4 h-4');

        echo <<<HTML
<div id="chatbot"
     style="display: none"
     class="w-[250px] md:w-[350px] !fixed bottom-[20px] right-[20px] bg-white shadow-xl rounded-3xl border border-zinc-200 p-4 z-20">
    <div class="flex justify-between w-full pb-2">
        <p class="text-zinc-800 font-bold">Gilou bot</p>
        <button class="p-1 rounded bg-zinc-800"
                onclick="closeChatbot()">
           $i_close_render
        </button>
    </div>
    <div id="chatbot-chat" class="w-full flex flex-col gap-2 h-[400px] overflow-y-auto example">
        <div class="flex w-full mt-2 space-x-3 max-w-xs">
            <div class="flex flex-col gap-2">
                <div class="bg-zinc-200 p-3 rounded-r-lg rounded-bl-lg">
                    <p class="text-sm">Salut $user_full_name ! Comment puis-je vous aider ?</p>
                </div>
                <span class="text-xs text-zinc-300 leading-none">Gilou</span>
            </div>
        </div>
    </div>
    <div class="flex justify-between w-full gap-2 mt-2">
        <label class="w-full">
            <input type="text" placeholder="Message"
                   id="chatbot-input"
                   class="shadow-sm w-full bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light">
        </label>
        <button onclick="sendMessage()"
                class="bg-blue-500 hover:bg-blue-600 focus:ring-blue-300 rounded-lg px-[14px]">
            $i_send_render
        </button>
    </div>
</div>
<button id="chatbot-button"
        onclick="openChatbot()"
        class="fixed bottom-[20px] right-[20px] bg-blue-500 hover:bg-blue-600 focus:ring-blue-300 rounded-full p-3 drop-shadow"> 
        $i_chat_render
</button>
HTML;
    }
}