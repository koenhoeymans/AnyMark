<?php

/**
 * @package AnyMark
 */
namespace AnyMark;

use AnyMark\Pattern\Pattern;
use ElementTree\Element;

/**
 * @package AnyMark
 */
class DummyPattern extends Pattern
{
    public function getRegex()
    {
    }

    public function handleMatch(
        array $match, Element $parent = null, Pattern $parentPattern = null
    ) {
    }
}
