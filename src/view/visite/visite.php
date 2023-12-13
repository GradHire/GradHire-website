<?php

use app\src\model\dataObject\Visite;
use app\src\model\Form\FormModel;

/**
 * @var Visite|null $visite
 * @var FormModel $form
 * @var string $name
 * @var string $addresse
 * @var array $commentaires
 */
?>

<div class="w-full flex flex-col justify-center content-center mt-5">
    <?php if ($visite != null) { ?>
        <h2 class="font-bold text-2xl ">Visite en entreprise pour la
            convention <?= $visite->getNumConvention() ?> de <?= $name ?></h2>
        <div class="text-gray-600 mb-4">
            Elle se déroulera le <?= $visite->getDebutVisite()->format("d/m/Y") ?>
            de <?= $visite->getDebutVisite()->format("H\hi") ?>
            à <?php echo $visite->getFinVisite()->format("H\hi") ?>
        </div>
        <p class="mb-2"><span class="font-bold">Adresse de l'entreprise:</span> <?= $addresse ?></p>
        <iframe
                class="w-full h-[300px] mb-10"
                src="https://maps.google.com/maps?q=<?= $addresse ?>&t=&z=13&ie=UTF8&iwloc=&output=embed">
        </iframe>
        <h2 class="font-bold text-2xl mb-4">Commentaires sur la visite</h2>
        <?php
        foreach ($commentaires as $commentaire) {
            if ($commentaire['commentaireprof'] != null) {
                echo <<<HTML
            <div class="flex flex-col gap-1 mb-4">
                <span class="font-bold">Tuteur universitaire :</span>
                 <p>{$commentaire['commentaireprof']}</p>
            </div>
            HTML;
            }
            if ($commentaire['commentaireentreprise'] != null) {
                echo <<<HTML
            <div class="flex flex-col gap-1 mb-4">
                <span class="font-bold">Tuteur entreprise :</span>
                 <p>{$commentaire['commentaireentreprise']}</p>
            </div>
            HTML;
            }
        }
        ?>
        <h2 class="font-bold text-2xl mb-4">Commentaires sur la soutenance</h2>
        <?php
        foreach ($commentaires as $commentaire) {
            if ($commentaire['commentairesoutenanceprof'] != null) {
                echo <<<HTML
                <div class="flex flex-col gap-1 mb-4">
                <span class="font-bold">Tuteur universitaire :</span>
                 <p>{$commentaire['commentairesoutenanceprof']}</p>
            </div>
            HTML;
            }
            if ($commentaire['commentairesoutenanceentreprise'] != null) {
                echo <<<HTML
                <div class="flex flex-col gap-1 mb-4">
                <span class="font-bold">Tuteur entreprise :</span>
                 <p>{$commentaire['commentairesoutenanceentreprise']}</p>
            </div>
            HTML;
            }
        }
    }
    if (isset($form)) {
        $form->start();
        ?>
        <div class="flex flex-col gap-4">
            <?php
            $form->print_all_fields();
            $form->submit("Enregistrer");
            $form->getError();
            ?>
        </div>
        <?php
        $form->end();
    }
    ?>
</div>

