<?php

/** @var $events Event[]
 * */

use app\src\core\components\Calendar\Calendar;
use app\src\core\components\Calendar\Event;

?>
<div class="mt-6 w-full">
    <p class="text-xl font-bold mb-4">Calendrier</p>
    <?php
    Calendar::render($events);
    ?>
</div>
