<?php

namespace app\src\view\components\calendar;

use app\src\core\exception\ServerErrorException;
use DateInterval;
use DateTime;
use IntlDateFormatter;

const DAYS = [
    'Monday' => 'Lundi',
    'Tuesday' => 'Mardi',
    'Wednesday' => 'Mercredi',
    'Thursday' => 'Jeudi',
    'Friday' => 'Vendredi',
    'Saturday' => 'Samedi',
    'Sunday' => 'Dimanche',
];

class Calendar
{
    /**
     * @param Event[] $events
     * @throws ServerErrorException
     */
    public static function render(array $events): void
    {
        $padding = count($events) == 0 ? 'pb-4' : 'mb-12 ';
        echo <<<HTML
            <div class="w-full $padding gap-2 pt-4 border-x-[1px] border-t-[1px] border-zinc-200 rounded-md flex flex-col relative bg-white drop-shadow-[10px]">
                <div class="absolute top-4 right-4 flex gap-2">
                    <div class="calendar-arrow disabled bg-blue-500 hover:bg-blue-600 rounded-lg" id="calendar-prev" onclick="calendarPrev()">
                        <span class="stroke-white text-white">&lt;</span>
                    </div>
                    <div class="calendar-arrow bg-blue-500 hover:bg-blue-600 rounded-lg" id="calendar-next" onclick="calendarNext()">
                        <span class="stroke-white text-white">&gt;</span>
                    </div>
                </div>
        HTML;

        if (count($events) == 0) {
            echo <<<HTML
                <div class="flex justify-center items-center h-full">
                    <p class="text-gray-400">Aucun événement</p>
                </div>
            HTML;
        } else {
            usort($events, function (Event $event1, Event $event2) {
                return $event1->getStart() <=> $event2->getStart();
            });
            $lastWeek = null;
            $first = true;
            $week = [];
            foreach ($events as $event) {
                if ($lastWeek == null || $lastWeek != $event->getStart()->format('W')) {
                    if ($lastWeek != null) {
                        self::renderWeek($week, $first);
                        $first = false;
                    }
                    $week = [];
                    $lastWeek = $event->getStart()->format('W');
                }
                $week[] = $event;
            }
            self::renderWeek($week, $first);
        }

        echo <<<HTML
            </div>
            <script src="/resources/js/calendar.js"></script>
        HTML;
    }

    /**
     * @param Event[] $events
     * @throws ServerErrorException
     */
    public static function renderWeek(array $events, bool $visible = false): void
    {
        setlocale(LC_TIME, "fr_FR");

        $bounds = self::getWeekBounds($events[0]->getStart());
        $start = IntlDateFormatter::formatObject
        (
            $bounds[0],
            'd MMMM',
            'fr'
        );
        $end = IntlDateFormatter::formatObject
        (
            $bounds[1],
            'd MMMM',
            'fr'
        );
        $hidden = $visible ? '' : 'hidden';
        echo <<<HTML
            <div class="flex flex-col calendar-week $hidden">
            <div class="flex justify-between border-b-[1px] border-zinc-200 pb-4 px-4">            
                <p class="max-md:text-[12px] max-w-[100px]">Semaine du $start au $end</p>
            </div>
        HTML;
        $lastDay = null;
        foreach ($events as $event) {
            if ($lastDay == null || $lastDay->format('Y-m-d') != $event->getStart()->format('Y-m-d')) {
                $day = DAYS[date_format($event->getStart(), 'l')];
                $fullDay = IntlDateFormatter::formatObject
                (
                    $event->getStart(),
                    'd MMMM y',
                    'fr'
                );
                echo <<<HTML
                    <div class=" border-b-[1px] bg-zinc-50 border-zinc-200 flex justify-between py-2 px-4">
                        <p class="font-bold">$day</p>
                        <p>$fullDay</p>
                    </div>
                HTML;
                $lastDay = $event->getStart();
            }
            self::renderEvent($event);
        }
        echo <<<HTML
            </div>
        HTML;
    }

    /**
     * @throws ServerErrorException
     */
    private static function getWeekBounds(DateTime $date): array
    {
        $clonedDate = clone $date;
        $dayOfWeek = (int)$clonedDate->format('N');
        try {
            $firstDayOfWeek = $clonedDate->sub(new DateInterval('P' . ($dayOfWeek - 1) . 'D'))->setTime(0, 0, 0);
            $lastDayOfWeek = clone $firstDayOfWeek;
            $lastDayOfWeek->add(new DateInterval('P6D'))->setTime(23, 59, 59);
            return [
                $firstDayOfWeek,
                $lastDayOfWeek,
            ];
        } catch (\Exception $e) {
            throw new ServerErrorException();
        }
    }

    private static function renderEvent(Event $event): void
    {
        echo <<<HTML
            <div class="flex cursor-pointer items-center hover:bg-zinc-100 p-2 gap-2 border-b-[1px] border-zinc-200">
                <div class="min-w-[40px] md:min-w-[100px] text-[10px] md:text-[14px]">
                    {$event->getStart()->format('H:i')} - {$event->getEnd()->format('H:i')}
                </div>
                <div class="rounded-full h-[10px] w-[10px]" style="background: {$event->getColor()}"></div>
                <div class="flex justify-between items-center w-full min-w-[40px] md:min-w-[100px] text-[10px] md:text-[14px]">                
                    {$event->getTitle()}
                    {$event->printBtn()}
                </div>
            </div>
        HTML;
    }

}