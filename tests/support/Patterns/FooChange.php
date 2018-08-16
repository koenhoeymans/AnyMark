<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Patterns;

use AnyMark\Pattern\Pattern;
use ElementTree\Element;

/**
 * @package AnyMark
 */
class FooChange extends \AnyMark\Pattern\Pattern
{
    public function getRegex() : string
    {
        return '@foo@';
    }

    public function handleMatch(
        array $match,
        Element $parent = null,
        \AnyMark\Api\Pattern $parentPattern = null
    ) : ?\ElementTree\Component {
        return $this->createText('bar');
    }
}
