<?php

/** @var $events \app\src\view\components\calendar\Event[]
 * */

use app\src\view\components\calendar\Calendar;
use app\src\view\components\calendar\Event;

?>
<div class="mt-6 w-full">
    <p class="text-xl font-bold mb-4">Calendrier</p>
    <?php
    Calendar::render($events);
    ?>
</div>
