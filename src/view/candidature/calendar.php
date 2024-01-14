<?php

/** @var $events \app\src\view\components\calendar\Event[]
 * */

use app\src\model\View;
use app\src\view\components\calendar\Calendar;

$this->title = 'Calendrier';
View::setCurrentSection('Calendrier');

Calendar::render($events);
