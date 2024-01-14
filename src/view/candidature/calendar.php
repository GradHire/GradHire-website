<?php

/** @var $events Event[]
 * */

use app\src\model\View;
use app\src\view\components\calendar\Calendar;
use app\src\view\components\calendar\Event;

$this->title = 'Calendrier';
View::setCurrentSection('Calendrier');

Calendar::render($events);
