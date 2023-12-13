<?php

/** @var $form FormModel */

use app\src\model\Form\FormModel;

?>

<div class="w-full max-w-md gap-4 flex flex-col gap-4 mx-auto mx-auto max-w-md">

    <h1 class="text-3xl font-bold text-center">Simulateur Pstage (Etudiant)</h1>
    <?php $form->start(); ?>
    <div class="w-full gap-4 flex flex-col" id="step1">
        <?php
        $form->print_fields(["typeStage", "Thématique", "Sujet", "fonction", "competence"]);
        ?>
        <button type="button" class="bg-blue-600 hover:bg-blue-700 rounded-lg p-2 text-white" onclick="nextStep('step1', 'step2')">Suivant</button>
    </div>
    <div class="w-full gap-4 flex flex-col hidden" id="step2">
        <?php
        $form->print_fields(["dateDebut", "dateFin", "interruption"]);
        ?>
        <div class="hidden" id="interr">
            <?php
            $form->print_fields(["dateDebutInterruption", "dateFinInterruption"]);
            ?>
        </div>
        <?php
        $form->print_fields(["duree", "nbJour", "nbHeure", "nbjourConge", "commentairetravail"]);
        ?>
        <button type="button" class="bg-zinc-500 hover:bg-zinc-600 rounded-lg p-2 text-white" onclick="prevStep('step2', 'step1')">Précédent</button>
        <button type="button"  class="bg-blue-600 hover:bg-blue-700 rounded-lg p-2 text-white" onclick="nextStep('step2', 'step3')">Suivant</button>
    </div>
    <div class="w-full gap-4 flex flex-col hidden" id="step3">
        <?php
        $form->print_fields(["gratification"]);
        ?>
        <div class="hidden" id="grat">
            <?php
            $form->print_fields(["montant", "heureoumois", "modalite"]);
            ?>
        </div>
        <?php
        $form->print_fields(["commenttrouve", "confconvention", "modalsuivi", "avantage"]);
        ?>
        <button type="button" class="bg-zinc-500 hover:bg-zinc-600 rounded-lg p-2 text-white" onclick="prevStep('step3', 'step2')">Précédent</button>
        <?php
        $form->submit("Envoyer");
        ?>
    </div>
    <?php
    $form->getError();
    $form->end();
    ?>

</div>
<script src="resources/js/stepsForm.js"></script>
