<?php
/** @var $form  \app\src\model\Form\FormModel */

$form->start();
$form->print_all_fields();
echo '<div class="flex items-end gap-2">';
$form->submit("Ajouter Soutenance");
echo '</div>';
$form->getError();
$form->getSuccess();
$form->end();
