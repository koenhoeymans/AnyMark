<?php

namespace AnyMark\Api;

use ElementTree\Component;

/**
 * When a pattern has found a match and returned an `ElementTree\Component`,
 * e.g. a `Text`, `Comment` or `Element`, this event is passed to subscribers
 * before it is inserted into the `ElementTree`.
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
