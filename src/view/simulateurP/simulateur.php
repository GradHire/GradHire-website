<?php
/** @var $form FormModel */

/** @var $nom String */

use app\src\model\Form\FormModel;

?>


<div class="w-full max-w-md gap-4 flex flex-col gap-4 mx-auto max-w-md">

    <h1 class="text-3xl font-bold text-center">Simulateur Pstage (<?php echo $nom ?>)</h1>
    <?php
    $form->start();
    ?>
    <?php if ($nom != "Etudiant") : ?>
        <div class="w-full max-w-md gap-4 flex flex-col gap-4 mx-auto mx-auto max-w-md">
            <div class="flex flex-row gap-2 items-center justify-between"><p> Le <?php echo $nom ?> n'existe pas
                    encore </p> <a href="/creer<?php echo $nom ?>"
                                   class="ml-3 inline-block rounded bg-zinc-800 px-4 py-2 text-xs font-medium w-fit text-white hover:bg-zinc-900">Créer-le</a>
            </div>
            <?php $form->print_all_fields(); ?>
        </div>
    <?php else : ?>
        <div class="w-full max-w-md gap-4 flex flex-col gap-4 mx-auto mx-auto max-w-md">
            <div class="w-full gap-4 flex flex-col" id="step1">
                <?php $form->print_fields(["numEtudiant", "nom", "prenom"]); ?>
                <button type="button" class="bg-blue-600 hover:bg-blue-700 rounded-lg p-2 text-white"
                        onclick="nextStep('step1', 'step2')">Suivant
                </button>
            </div>
            <div class="w-full gap-4 flex flex-col hidden" id="step2">
                <?php $form->print_fields(["adresse", "codePostal", "ville", "telephone", "emailPerso", "emailUniv"]); ?>
                <button type="button" class="bg-zinc-500 hover:bg-zinc-600 rounded-lg p-2 text-white"
                        onclick="prevStep('step2', 'step1')">Précédent
                </button>
                <button type="button" class="bg-blue-600 hover:bg-blue-700 rounded-lg p-2 text-white"
                        onclick="nextStep('step2', 'step3')">Suivant
                </button>
            </div>
            <div class="w-full gap-4 flex flex-col hidden" id="step3">
                <?php $form->print_fields(["CPAM", "anneeUni", "nbHeure"]); ?>
                <button type="button" onclick="prevStep('step3', 'step2')"
                        class="bg-zinc-500 hover:bg-zinc-600 rounded-lg p-2 text-white">Précédent
                </button>
                <?php $form->submit("Continuer"); ?>
            </div>
        </div>
    <?php endif;
    if ($nom != "Etudiant") {
        echo "<div class='mt-2'>";
        $form->submit("Continuer");
        echo "</div>";

    }
    $form->getError();
    $form->end();
    ?>
</div>
<script src="resources/js/stepsForm.js"></script>
