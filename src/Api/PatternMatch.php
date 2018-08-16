<?php

namespace AnyMark\Api;

/**
 * When a pattern has handled a match and created an element this
 * event is thrown.
 */
interface PatternMatch
{
    /**
     * The element (or comment) that is created by the pattern in response
     * to the match.
     */
    public function getComponent() : \ElementTree\Component;

    /**
     * The pattern that generated the match.
     */
    public function getPattern() : \AnyMark\Api\Pattern;
}
