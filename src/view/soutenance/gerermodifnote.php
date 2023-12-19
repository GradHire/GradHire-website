<?php

use app\src\model\dataObject\Notes;
use app\src\model\Form\FormModel;

/* @var $note Notes */
/* @var $form FormModel */
?>
<div class="w-full max-w-md gap-4 flex flex-col gap-4 mx-auto max-w-md">
    <h1 class="text-3xl font-bold text-center">Modifier la note de <?php echo $note->getEtudiant() ?></h1>
    <?php
    $form->start();
    $form->print_all_fields();
    $form->submit("Modifier");
    $form->getError();
    $form->end();
    ?>
</div>
