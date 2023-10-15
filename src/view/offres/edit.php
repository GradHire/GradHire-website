<?php
/** @var $form FormModel */

use app\src\model\Form\FormModel;

$this->title = 'Modifier offre';

?>
<div class="w-full max-w-md pt-12 pb-24 gap-4 flex flex-col">
    <?php $form->start(); ?>
    <div class="w-full gap-4 flex flex-col">
        <?php
        $form->print_fields(["sujet", "thematique"]);
        echo "<div class='flex flex-row w-full gap-4 items-center'>";
        $form->print_fields(["dateDebut", "dateDebut"]);
        echo "</div>";
        echo "<div class='flex flex-row w-full gap-4 items-center'>";
        $form->print_fields(["nbHeureTravailHebdo", "nbjourtravailhebdo"]);
        echo "</div>";
        echo "<div class='flex flex-row w-full gap-4 items-center'>";
        $form->print_fields(["gratification", "unitegratification"]);
        echo "</div>";
        $form->print_fields(["avantageNature", "anneeVisee", "description"]);
        $form->submit("Enregistrer les modifications");
        $form->getError();
        ?>
    </div>
    <?php $form->end(); ?>
</div>
<script>
    const dateDebut = document.getElementById("dateDebut");
    const dateFin = document.getElementById("dateFin");
    const submit = document.getElementById("submit");
    submit.addEventListener("click", function (e) {
        if (dateDebut.value > dateFin.value) {
            e.preventDefault();
            alert("La date de début doit être inférieure à la date de fin");
        }
    });
</script>