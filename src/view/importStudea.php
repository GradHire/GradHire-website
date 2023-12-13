<?php

/** @var $form FormModel */

use app\src\model\Form\FormModel;

?>

<div class="w-full max-w-md gap-4 flex flex-col mx-auto">

    <?php $form->start(); ?>
    <div class="w-full gap-4 flex flex-col">
        <?php
        $form->print_all_fields();
        $form->submit("Importer");
        $form->getError();
        ?>
    </div>
    <?php $form->end(); ?>
</div>
