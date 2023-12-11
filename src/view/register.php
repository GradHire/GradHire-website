<?php
/** @var $form FormModel */

use app\src\model\Form\FormModel;

$this->title = 'Register';

?>

<div class="w-full max-w-md gap-4 mx-auto gap-2 flex flex-col">
    <h2 class="text-3xl">Nouveau compte Pro</h2>
    <span class="text-zinc-600 mb-5">Vous avez déjà un compte ? <a href="/pro_login"
                                                                   class="underline">Se connecter</a></span>
    <?php $form->start(); ?>
    <div class="w-full gap-4 flex flex-col">
        <?php
        $form->print_all_fields();
        $form->getError();
        $form->submit("Créer un compte");
        ?>
    </div>
    <?php $form->end(); ?>
    <div class="text-center mt-8 mb-4">
        <hr class="border-t-2 border-zinc-300 w-full mx-auto">
        <span class="bg-white px-2 relative" style="top: -0.75rem;">ou</span>
    </div>
    <?php $form->linkBtn("Connexion via LDAP", "/login"); ?>
</div>
