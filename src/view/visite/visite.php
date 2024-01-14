<?php

use app\src\model\dataObject\Visite;
use app\src\model\Form\FormModel;
use app\src\model\View;

/**
 * @var Visite|null $visite
 * @var FormModel $form
 * @var string $name
 * @var string $addresse
 * @var array $commentaires
 */

$this->title = 'Visite';
View::setCurrentSection('Visites');
?>
<div class="w-full gap-2 mx-auto flex flex-col md:pt-[50px] border bg-white p-2 md:p-4 rounded-md drop-shadow-[10px]">
    <?php if ($visite != null) : ?>
        <div class="mb-4 text-center">
            <h2 class="md:text-3xl text-xl font-bold text-zinc-900">
                Visite en entreprise - Convention <?= htmlspecialchars($visite->getNumConvention()) ?>
            </h2>
            <div class="text-gray-700 mt-2">
                Prévue pour le <?= htmlspecialchars($visite->getDebutVisite()->format("d/m/Y")) ?>,
                de <?= htmlspecialchars($visite->getDebutVisite()->format("H\hi")) ?>
                à <?= htmlspecialchars($visite->getFinVisite()->format("H\hi")) ?>
            </div>
            <p class="font-semibold text-gray-800 mt-4">
                <span class="text-lg">Adresse de l'entreprise:</span> <?= htmlspecialchars($addresse) ?>
            </p>
        </div>

        <div class="map-container mb-8">
            <iframe class="w-full h-[300px] rounded-md shadow" style="border:0;"
                    src="https://maps.google.com/maps?q=<?= urlencode($addresse) ?>&t=&z=13&ie=UTF8&iwloc=&output=embed"
                    allowfullscreen loading="lazy"></iframe>
        </div>

        <?php if (count($commentaires) > 0) : ?>
            <div class="comments-section ">
                <h2 class="md:text-xl text-lg text-gray-800 mb-3">Commentaires sur la visite : </h2>
                <?php foreach ($commentaires as $commentaire) : ?>
                    <?php if (!empty($commentaire['commentaireprof'])) : ?>
                        <div class="bg-gray-100 p-4 rounded-md shadow mb-4">
                            <h3 class="font-bold">Tuteur universitaire :</h3>
                            <p><?= htmlspecialchars($commentaire['commentaireprof']) ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($commentaire['commentaireentreprise'])) : ?>
                        <div class="bg-gray-100 p-4 rounded-md shadow mb-4">
                            <h3 class="font-bold">Tuteur entreprise :</h3>
                            <p><?= htmlspecialchars($commentaire['commentaireentreprise']) ?></p>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (isset($form)) : ?>
        <div class="form-section">
            <?php $form->start(); ?>
            <div class="grid grid-cols-1 gap-6 mb-6">
                <?php $form->print_all_fields(); ?>
            </div>
            <?php $form->getError(); ?>
            <div class="text-center">
                <?php $form->submit("Enregistrer", 1, "bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded cursor-pointer"); ?>
            </div>
            <?php $form->end(); ?>
        </div>
    <?php endif; ?>

</div>
