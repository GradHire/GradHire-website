<?php
/** @var $form FormModel */

use app\src\model\Form\FormModel; ?>

<div class="w-full gap-4 flex flex-col">
    <?php
    $form->start();
    $form->print_all_fields();
    $form->submit();
    ?>
</div>
<?php

$form->getError();
$form->end();
?>

