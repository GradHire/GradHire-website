<?php

/** @var $form FormModel */

use app\src\model\Form\FormModel;

?>

<div class="w-full max-w-md gap-4 flex flex-col pt-12 pb-24">

    <h1 class="text-3xl font-bold text-center">Recherchez l'établissement où le stage sera effectué :</h1>
    <?php $form->start(); ?>
    <div class="w-full gap-4 flex flex-col">
        <?php
        $form->print_fields(["typeRecherche"]);
        ?>
    </div>
    <div class="w-full gap-4 flex flex-col" id="nomEnt">
        <?php
        $form->print_fields(["nomEnt", "pays", "department"]);
        $form->submit("Rechercher");
        ?>
    </div>
    <div class="w-full gap-4 flex flex-col hidden" id="numTel">
        <?php
        $form->print_fields(["tel", "fax"]);
        $form->submit("Rechercher");
        ?>
    </div>
    <div class="w-full gap-4 flex flex-col hidden" id="adresse">
        <?php
        $form->print_fields(["adresse", "codePostal", "pays"]);
        $form->submit("Rechercher");
        ?>
    </div>
    <div class="w-full gap-4 flex flex-col hidden" id="numsiret">
        <?php
        $form->print_fields(["siret", "siren"]);
        $form->submit("Rechercher");
        ?>
        <?php
        $form->getError();
        $form->end();
        ?>
    </div>
    <script src="resources/js/stepsForm.js"></script>