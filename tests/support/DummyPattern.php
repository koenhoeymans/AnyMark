<?php

namespace AnyMark;

use AnyMark\Pattern\Pattern;

class DummyPattern extends Pattern
{
    public function getRegex(): string
    {
    }

    public function handleMatch(
        array $match,
        \ElementTree\Element $parent = null,
        \AnyMark\Api\Pattern $parentPattern = null
    ): \ElementTree\Component {
    }
}
