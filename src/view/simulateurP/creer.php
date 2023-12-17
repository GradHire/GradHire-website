<?php

/** @var $form FormModel */

/** @var $nom String */

use app\src\model\Form\FormModel;

?>

<div class="w-full max-w-md gap-4 flex flex-col gap-4 mx-auto mx-auto max-w-md">
    <h1 class="text-3xl font-bold text-center">Simulateur Pstage <?php echo $nom ?></h1>

    <?php
    $form->start();
    $form->print_all_fields();
    $form->submit("Créer");
    $form->getError();
    $form->end();
    ?>
</div>
