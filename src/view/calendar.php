<?php

/** @var $events Event[]
 * */

use app\src\core\components\Calendar\Calendar;
use app\src\core\components\Calendar\Event;

?>
<div class="mt-12 w-full">
    <?php
    Calendar::render($events);
    ?>
</div>
