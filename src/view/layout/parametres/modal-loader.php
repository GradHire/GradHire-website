<?php

use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Form\FormModel;
use app\src\view\components\ui\FormModal;

/**
 * @var array $allSections
 * @var array $allActions
 * @var array $sections
 * @var array $actions
 */

/**
 * @param $items
 * @param $selectedItems
 * @param $defaultKey
 * @return FormModal
 * @throws ServerErrorException
 */


function generateFormCheckboxes($items, $selectedItems, $defaultKey): FormModal
{
    $checkboxes = [];

    foreach ($items as $itemId => $item) {
        if ($itemId === 'S12' || $itemId === 'S11') continue;
        $checkbox = FormModel::checkbox("", [$itemId => $item['nom']]);
        $checkboxes[$itemId] = $checkbox;
        if (in_array($itemId, $selectedItems)) $checkbox->default([$itemId]);
    }

    return new FormModal(function () use ($checkboxes, $defaultKey) {
        $form = new FormModel($checkboxes);
        $form->setAction("/modifierParametres/{$defaultKey}?" . Application::getRedirect());
        $form->start();
        echo "<div class='flex flex-col justify-center items-center gap-2 mb-4'>";
        echo "<span class='text-xl font-bold'>Ajouter une {$defaultKey} dans le menu</span>";
        foreach ($checkboxes as $itemId => $checkbox) $form->field($itemId);
        echo "</div>";
        $form->submit("Enregistrer");
        $form->end();
    });
}

try {
    $modalAddSection = generateFormCheckboxes($allSections, $sections, "sections");
    $modalAddAction = generateFormCheckboxes($allActions, $actions, "actions");

} catch (ServerErrorException $e) {
    $modalAddSection = null;
    $modalAddAction = null;
}