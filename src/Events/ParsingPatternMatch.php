<?php

namespace AnyMark\Events;

use AnyMark\Pattern\Pattern;
use ElementTree\Component;
use Epa\Api\Event;
use AnyMark\PublicApi\PatternMatch;

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
     * @see \AnyMark\PublicApi\PatternMatch::getComponent()
     */
    public function getComponent() : Component
    {
        return $this->component;
    }

    /**
     * @see \AnyMark\PublicApi\PatternMatch::getPattern()
     */
    public function getPattern() : Pattern
    {
        return $this->pattern;
    }
}
