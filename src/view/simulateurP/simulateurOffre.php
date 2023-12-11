<?php

/** @var $form2 FormModel */

use app\src\model\Form\FormModel;

?>

<div class="w-full max-w-md gap-4 flex flex-col pt-12 pb-24 mx-auto max-w-md">

    <h1 class="text-3xl font-bold text-center">Recherchez l'établissement où le stage sera effectué :</h1>
    <?php $form2->start(); ?>
    <div class="w-full gap-4 flex flex-col">
        <?php
        $form2->print_fields(["typeRecherche"]);
        ?>
    </div>
    <div class="w-full gap-4 flex flex-col" id="nomEnt">
        <?php
        $form2->print_fields(["nomEnt", "pays", "department"]);
        ?>
    </div>
    <div class="w-full gap-4 flex flex-col hidden" id="numTel">
        <?php
        $form2->print_fields(["tel", "fax"]);
        ?>
    </div>
    <div class="w-full gap-4 flex flex-col hidden" id="adresse">
        <?php
        $form2->print_fields(["adresse", "codePostal", "pays"]);
        ?>
    </div>
    <div class="w-full gap-4 flex flex-col hidden" id="numsiret">
        <?php
        $form2->print_fields(["siret", "siren"]);
        ?>

    </div>
    <div class="w-full gap-4 flex flex-col mt-4">

        <?php
        $form2->submit("Rechercher");
        $form2->getError();
        $form2->end();
        ?>
        <div class="flex">
            <p> L'entreprise n'existe pas encore </p>
            <a href="/creerEntreprise"
               class="ml-3 inline-block rounded bg-zinc-600 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700">Créer-la</a>
        </div>
    </div>
    <script src="resources/js/stepsForm.js"></script>
