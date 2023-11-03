<?php

/** @var $form FormModel */

use app\src\model\Form\FormModel;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['form_data'] = $_POST;
}

?>

<div class="w-full max-w-md gap-4 flex flex-col pt-12 pb-24">

    <h1 class="text-3xl font-bold text-center">Simulateur Pstage (Etudiant)</h1>
    <?php $form->start(); ?>
    <div class="w-full gap-4 flex flex-col">
        <?php
        $form->print_all_fields();
        $form->submit("Suivant");
        $form->getError();
        ?>
    </div>
    <?php $form->end(); ?>

</div>