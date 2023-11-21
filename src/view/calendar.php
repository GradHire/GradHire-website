<?php

use app\src\core\components\Calendar\Calendar;
use app\src\core\components\Calendar\Event;
use app\src\core\components\FormModal;

$event1 = new Event(
    'Event 1',
    'Description event 1',
    new \DateTime('2021-01-03 12:00:00'),
    new \DateTime('2021-01-03 13:00:00')
);

$event2 = new Event(
    'Event 2',
    'Description event 2',
    new \DateTime('2021-01-02 11:00:00'),
    new \DateTime('2021-01-02 12:00:00')
);

$event3 = new Event(
    'Event 3',
    'Description event 3',
    new \DateTime('2021-01-10 12:00:00'),
    new \DateTime('2021-01-10 12:30:00')
);

$event4 = new Event(
    'Event 4',
    'Description event 4',
    new \DateTime('2021-02-10 12:00:00'),
    new \DateTime('2021-02-10 14:00:00')
);

$modal = new FormModal("<h3>1feur</h3>");

Calendar::addModal("Ajouter un événement", $modal);
Calendar::render([$event1, $event2, $event3, $event4]);

?>
