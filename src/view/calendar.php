<?php

/** @var $events Event[] */

use app\src\core\components\Calendar\Calendar;
use app\src\core\components\Calendar\Event;
use app\src\core\components\FormModal;

$modal = new FormModal("<h3>1feur</h3>");

Calendar::addModal("Ajouter un événement", $modal);
Calendar::render($events);
