<?php

/** @var $events Event[]
 * @var $visiteModal FormModal
 * */

use app\src\core\components\Calendar\Calendar;
use app\src\core\components\Calendar\Event;
use app\src\core\components\FormModal;

$newEventModal = new FormModal("<h3>Ajouter un événement</h3>");

$visiteModal->render("Visite");
?>
<div class="mt-12 w-full">
    <?php
    Calendar::addModal("Ajouter un événement", $newEventModal);
    Calendar::render($events);
    ?>
</div>
