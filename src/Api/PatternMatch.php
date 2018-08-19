<?php

namespace AnyMark\Api;

use ElementTree\Component;

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
    public function getComponent(): Component;

    /**
     * The pattern that generated the match.
     */
    public function getPattern(): Pattern;
}
