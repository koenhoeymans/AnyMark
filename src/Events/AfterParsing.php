<?php

namespace AnyMark\Events;

use Epa\Api\Event;
use ElementTree\ElementTree;
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
