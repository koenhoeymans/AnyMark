<?php

namespace AnyMark\Events;

use AnyMark\Api\Pattern;
use AnyMark\Api\PatternMatch;
use ElementTree\Component;
use Epa\Api\Event;

class ParsingPatternMatch implements Event, PatternMatch
{
    private $component;

    private $pattern;

    public function __construct(Component $component, Pattern $pattern)
    {
        $this->component = $component;
        $this->pattern = $pattern;
    }

    /**
     * @see \AnyMark\Api\PatternMatch::getComponent()
     */
    public function getComponent(): Component
    {
        return $this->component;
    }

    /**
     * @see \AnyMark\Api\PatternMatch::getPattern()
     */
    public function getPattern(): Pattern
    {
        return $this->pattern;
    }
}
