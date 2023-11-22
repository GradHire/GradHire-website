<?php

namespace app\src\core\components\Calendar;

class Event
{
    private string $title;
    private string $description;
    private \DateTime $start;
    private \DateTime $end;

    public function __construct(string $title, string $description, \DateTime $start, \DateTime $end)
    {
        $this->title = $title;
        $this->description = $description;
        $this->start = $start;
        $this->end = $end;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStart(): \DateTime
    {
        return $this->start;
    }

    public function getEnd(): \DateTime
    {
        return $this->end;
    }
}