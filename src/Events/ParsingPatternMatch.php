<?php

namespace AnyMark\Events;

use AnyMark\Pattern\Pattern;
use ElementTree\Component;
use Epa\Api\Event;
use AnyMark\Api\PatternMatch;

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
    public function getComponent() : Component
    {
        return $this->component;
    }

    /**
     * @see \AnyMark\Api\PatternMatch::getPattern()
     */
    public function getPattern() : \AnyMark\Api\Pattern
    {
        return $this->pattern;
    }
}
