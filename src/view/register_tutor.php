<?php

/** @var $enterprise string */

/** @var $form FormModel */

use app\src\model\Form\FormModel;

$this->title = 'Register';

?>

<div class="w-full max-w-md gap-4 mx-auto gap-2 flex flex-col">
    <h2 class="text-3xl">Creation compte tuteur</h2>
    <span class="text-zinc-600 mb-5">Votre compte sera lier à l'entreprise <?= $enterprise ?></span>
    <?php $form->start(); ?>
    <div class="w-full gap-4 flex flex-col">
        <?php
        $form->print_all_fields();
        $form->getError();
        $form->submit("Créer mon compte");
        ?>
    </div>
    <?php $form->end(); ?>
</div>
