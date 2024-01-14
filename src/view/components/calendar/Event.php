<?php

namespace app\src\view\components\calendar;

use DateTime;

class Event
{
    private string $title;
    private DateTime $start;
    private DateTime $end;
    private string $color;
    private string $btn;
    private string $url;

    public function __construct(string $title, DateTime $start, DateTime $end, string $color)
    {
        $this->title = $title;
        $this->start = $start;
        $this->end = $end;
        $this->color = $color;
        $this->btn = "";
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getStart(): DateTime
    {
        return $this->start;
    }

    public function getEnd(): DateTime
    {
        return $this->end;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function setButton(string $text, string $action)
    {
        $this->btn = $text;
        $this->url = $action;
    }

    public function printBtn(): string
    {
        if ($this->btn !== "") {
            return <<<HTML
<a href="$this->url"
	   class="inline-block rounded ml-2 px-2 py-1 md:px-4 md:py-2 text-[10px] text-center md:text-xs font-medium text-white bg-zinc-800 hover:bg-zinc-900">$this->btn</a>
HTML;
        }
        return '';
    }
}