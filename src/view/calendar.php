<?php

/** @var $events Event[]
 * @var $visiteModal FormModal
 * @var $soutenanceModal FormModal
 * */

use app\src\core\components\Calendar\Calendar;
use app\src\core\components\Calendar\Event;
use app\src\core\components\FormModal;

$newEventModal = new FormModal("<h3>Ajouter un événement</h3>");

$visiteModal->render("Visite");
$soutenanceModal->render("Soutenance");
?>
<div class="mt-12 w-full">
    <?php
    Calendar::addModal("Ajouter un événement", $newEventModal);
    Calendar::render($events);
    ?>
</div>
