<?php

/** @var $events Event[]
 * @var $visiteModal FormModal
 * @var $soutenanceModal FormModal
 * */

use app\src\core\components\Calendar\Calendar;
use app\src\core\components\Calendar\Event;
use app\src\core\components\FormModal;

$visiteModal->render("Visite");
$soutenanceModal->render("Soutenance");
?>
<div class="mt-12 w-full">
    <?php
    Calendar::render($events);
    ?>
</div>
