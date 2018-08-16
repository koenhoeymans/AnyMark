<?php

namespace AnyMark\Events;

use ElementTree\ElementTree;
use Epa\Api\Event;
use AnyMark\Api\AfterParsingEvent;

class AfterParsing implements Event, AfterParsingEvent
{
    private $tree;

    public function __construct(ElementTree $tree)
    {
        $this->tree = $tree;
    }

    public function getTree() : ElementTree
    {
        return $this->tree;
    }
}