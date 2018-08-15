<?php

namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;
use ElementTree\Element;

class NewLine extends Pattern
{
    public function getRegex() : string
    {
        return "@[ ][ ](?=\n)@";
    }

    public function handleMatch(
        array $match,
        Element $parent = null,
        Pattern $parentPattern = null
    ) : Element {
        return $this->createElement('br');
    }
}
