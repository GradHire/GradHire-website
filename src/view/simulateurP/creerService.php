<?php

/** @var $form FormModel */

use app\src\model\Form\FormModel;

?>

<div class="w-full max-w-md gap-4 flex flex-col gap-4 mx-auto mx-auto max-w-md">

    <h1 class="text-3xl font-bold text-center">Simulateur Pstage (Service)</h1>
    <?php $form->start(); ?>
    <div class="w-full gap-4 flex flex-col" id="step1">
        <?php
        $form->print_fields(["nomService", "memeAdresse"]);
        ?>
        <div class="w-full gap-4 flex flex-col hidden" id="adr">
            <?php
            $form->print_fields(["voie", "residence", "cp", "ville", "pays"]);
            ?>
        </div>

        <?php
        $form->submit("Continuer");
        ?>
    </div>
    <?php
    $form->getError();
    $form->end();
    ?>

</div>
<script src="resources/js/stepsForm.js"></script>
