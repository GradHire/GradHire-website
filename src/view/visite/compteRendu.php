<div class="mt-12 w-full">
    <?php
    /** @var $form  FormModel */

    use app\src\model\Form\FormModel;

    $form->start();
    $form->print_all_fields();
    echo '<div class="flex items-end gap-2">';
    $form->submit("Validé compte rendu de visite");
    echo '</div>';
    $form->getError();
    $form->getSuccess();
    $form->end();
    ?>
</div>