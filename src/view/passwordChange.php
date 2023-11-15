<?php

/** @var $form FormModel */

use app\src\model\Form\FormModel;

$this->title = 'Login';

?>

<div class="w-full max-w-md pt-12 pb-24 gap-2 flex flex-col">

    <h2 class="text-3xl">Modifier mot de passe</h2>
    <?php $form->start(); ?>
    <div class="w-full gap-4 flex flex-col">
        <?php
        $form->print_all_fields();
        $form->getError();
        $form->submit("Enregistrer");
        ?>
    </div>
    <?php $form->end(); ?>
    <?php $form->linkBtn("Annuler", "/profile"); ?>
</div>
