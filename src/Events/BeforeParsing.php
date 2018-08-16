<?php

namespace AnyMark\Events;

use Epa\Api\Event;
use AnyMark\Api\BeforeParsingEvent;

class BeforeParsing implements Event, BeforeParsingEvent
{
    private $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function getText() : string
    {
        return $this->text;
    }

    public function setText(string $text) : void
    {
        $this->text = $text;
    }
}
