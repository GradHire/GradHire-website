<?php

/** @var $form FormModel */

use app\src\model\Form\FormModel;

?>
<div class="w-full max-w-md pt-12 pb-24 gap-2 flex flex-col">
    <div class="w-full gap-4 flex flex-col">
        <?php
        $form->start();

        $form->print_all_fields();
        $form->getError();
        ?>
        <div class="w-full flex justify-end mt-2">
            <?php
            $form->submit("Envoyer");
            ?>
        </div>
        <?php

        $form->end();

        ?>
    </div>
</div>
