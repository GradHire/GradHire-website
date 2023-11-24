<?php

namespace app\src\core\components\Calendar;

use app\src\core\components\FormModal;

class Event
{
    private string $title;
    private string $description;
    private \DateTime $start;
    private \DateTime $end;
    private string $color;
    private FormModal $modal;

    public function __construct(string $title, string $description, \DateTime $start, \DateTime $end, string $color, FormModal $modal)
    {
        $this->title = $title;
        $this->description = $description;
        $this->start = $start;
        $this->end = $end;
        $this->color = $color;
        $this->modal = $modal;
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

    public function getColor(): string
    {
        return $this->color;
    }

    public function getModal(): FormModal
    {
        return $this->modal;
    }
}