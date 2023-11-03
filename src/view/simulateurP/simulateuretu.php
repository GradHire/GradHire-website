<?php

/** @var $form FormModel */

/** @var $form2 FormModel */

use app\src\model\Form\FormModel;

?>

<div class="w-full max-w-md gap-4 flex flex-col pt-12 pb-24">

    <h1 class="text-3xl font-bold text-center">Simulateur Pstage</h1>
    <?php $form->start(); ?>
    <div class="w-full gap-4 flex flex-col">
        <?php
        $form->print_all_fields();
        $form->submit("Envoyer");
        $form->getError();
        ?>
    </div>
    <?php $form->end(); ?>

</div>
