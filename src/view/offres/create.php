<?php

/** @var $form FormModel */

/** @var $draft FormModel */

/** @var $offre OffresRepository */

/** @var $enterprises FormModel */

use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\Form\FormModel;
use app\src\model\repository\OffresRepository;
use app\src\model\View;

$this->title = 'Creation d\'offre';
View::setCurrentSection('Offres');

Auth::check_role(Roles::Enterprise, Roles::Manager, Roles::Staff);

?>
<div class="w-full max-w-md gap-4 mx-auto flex flex-col md:py-[50px] pb-4">
    <?php
    if (Auth::has_role(Roles::Enterprise)) {
        $draft->start();
        $draft->print_all_fields();
        $draft->end();
    }
    $form->start();
    if (!Auth::has_role(Roles::Enterprise))
        $enterprises->print_all_fields();
    ?>
    <div class="w-full gap-4 flex flex-col">
        <?php
        $form->print_fields(["typeStage", "sujet", "theme"]);
        ?>
        <div class="flex gap-2 items-end" id="time">
            <?php
            $form->print_fields(["nbjourtravailhebdo", "nbheureparjour", 'distanciel']);
            ?>
        </div>
        <?php
        $form->print_fields(["gratification", "avantage"]);
        ?>
        <div class="flex gap-2">
            <?php
            $form->print_fields(["datedebut", "datefin"]);
            ?>
        </div>
        <div class="flex gap-2" id="durations">
            <?php
            $form->print_fields(["dureeAlternance", "dureeStage"]);
            ?>
        </div>
        <?php
        $form->print_fields(["description"]);
        $form->getError();
        ?>
        <div class="flex gap-2">
            <?php
            if (Auth::has_role(Roles::Enterprise)) {
                $form->submit("Sauvegarder", 1, "save");
                if ($offre) $form->submit("Supprimer brouillon", 3, "delete");
            }
            ?>
        </div><?php
        $form->submit("Publier", 2, "send");
        ?>
    </div>
    <?php
    $form->end();
    ?>
</div>
<script src="/resources/js/createOffre.js"></script>