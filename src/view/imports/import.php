<?php

/** @var $form FormModel */

use app\src\model\Form\FormModel;
use app\src\model\View;

View::setCurrentSection('Imports');
$this->title = 'Imports';


?>

<div class="w-full max-w-md gap-4 mx-auto flex flex-col py-[50px]">
    <h2 class="text-xl font-bold text-center">Import Pstage / Studea / Scodoc</h2>
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
