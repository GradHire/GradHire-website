<?php

/** @var $form FormModel */

use app\src\model\Form\FormModel;

?>

<div class="w-full max-w-md gap-4 flex flex-col gap-4 mx-auto mx-auto max-w-md">

    <h1 class="text-3xl font-bold text-center">Simulateur Pstage (Professeur Référent)</h1>
    <?php $form->start(); ?>
    <div class="w-full gap-4 flex flex-col">
        <?php
        $form->print_all_fields();
        ?>
    </div>
    <div class="w-full gap-4 flex flex-col mt-2 mb-2">
        <?php
        $form->submit("Rechercher");
        ?>
    </div>
    <?php

    $form->getError();
    $form->end();
    ?>

</div>

