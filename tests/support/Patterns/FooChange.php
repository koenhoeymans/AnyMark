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
    public function getRegex()
    {
        return '@foo@';
    }

    public function handleMatch(
        array $match,
        Element $parent = null,
        Pattern $parentPattern = null
    ) {
        return $this->createText('bar');
    }
}
