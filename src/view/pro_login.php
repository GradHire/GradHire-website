<?php

/** @var $form FormModel */

use app\src\model\Form\FormModel;

$this->title = 'Login';
?>


<div class="w-full max-w-md gap-4 mx-auto gap-2 flex flex-col">
    <h2 class="text-3xl">Connexion Pro</h2>
    <span class="text-zinc-600 mb-5">Pas encore de compte ? <a href="/register"
                                                               class="underline">Créer un compte</a></span>
    <?php $form->start(); ?>
    <div class="w-full gap-4 flex flex-col">
        <?php
        $form->print_all_fields();
        $form->getError();
        $form->submit("Se connecter");
        ?>
    </div>
    <?php $form->end(); ?>
    <a href="/resetPassword" class="underline text-zinc-700 mt-2">Mot de passe oublié ?</a>
    <div class="text-center mt-8 mb-4">
        <hr class="border-t-1 border-zinc-300 w-full mx-auto">
        <span class="bg-white px-2 relative" style="top: -0.75rem;">ou</span>
    </div>
    <?php $form->linkBtn("Connexion via LDAP", "/login"); ?>
</div>

