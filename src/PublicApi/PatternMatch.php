<?php

/**
 * @package AnyMark
 */
namespace AnyMark\PublicApi;

use AnyMark\Pattern\Pattern;

/**
 * When a pattern has handled a match (eg created an element) this
 * event is thrown.
 *
 * @package AnyMark
 * @eventname PatternMatch
 */
interface PatternMatch
{
    /**
     * The element (or comment) that is created by the pattern in response
     * to the match.
     *
     * @return \ElementTree\Component;
     */
    public function getComponent();

    /**
     * The pattern that generated the match.
     *
     * @return Pattern
     */
    public function getPattern();
}
