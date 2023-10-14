<?php

/** @var $form FormModel */

use app\src\model\Form\FormModel;

?>

<div class="w-full pt-12 pb-24 gap-2 flex flex-col">

    <?php $form->start(); ?>
    <div class="w-full gap-4 flex flex-col">
        <?php
        $form->print_all_fields();
        ?>
        <div class="w-full flex lg:flex-row flex-col gap-2">
            <?php
            $form->submit("Se connecter");
            ?>
            <a href="pro_login" class="text-white w-full max-w-[30%] bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">Connexion en tant que professionnel
            </a>
        </div>
        <?php
        $form->getError();
        ?>
    </div>
    <?php $form->end(); ?>
</div>
