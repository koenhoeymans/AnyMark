<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;
use ElementTree\Element;

/**
 * @package AnyMark
 */
class NewLine extends Pattern
{
    public function getRegex()
    {
        return "@[ ][ ](?=\n)@";
    }

    public function handleMatch(
        array $match, Element $parent = null, Pattern $parentPattern = null
    ) {
        return $this->createElement('br');
    }
}
