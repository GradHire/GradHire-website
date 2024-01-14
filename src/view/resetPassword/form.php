<?php

/**
 * @var FormModel $form
 */

use app\src\model\Form\FormModel;

?>

<div class="w-full gap-4 mx-auto flex flex-col max-w-md py-[50px]">
    <h2 class="text-3xl">Modifier mon mot de passe</h2>
    <span class="text-zinc-600 mb-5">Veuillez renseigner l'adresse mail de votre compte.</span>
    <?php
    $form->start();
    $form->print_all_fields();
    ?>
    <div class="w-full mt-4">
        <?php
        $form->submit("Ok");
        ?>
    </div><?php
    $form->getSuccess();
    $form->getError();
    $form->end();
    ?>
</div>