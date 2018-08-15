<?php

namespace AnyMark\Pattern;

use ElementTree\Element;
use ElementTree\ElementTreeElement;
use ElementTree\ElementTreeText;
use ElementTree\ElementTreeComment;

/**
 * When a text matches its pattern it transforms it.
 */
abstract class Pattern implements Api\Pattern
{
    abstract public function getRegex() : string;

    abstract public function handleMatch(
        array $match,
        Element $parent = null,
        Pattern $parentPattern = null
    ) : \ElementTree\Component;

    public function createElement(string $name) : \ElementTree\Element
    {
        return new ElementTreeElement($name);
    }

    public function createText(string $text) : \ElementTree\Element
    {
        return new ElementTreeText($text);
    }

    public function createComment(string $text) : \ElementTree\Comment
    {
        return new ElementTreeComment($text);
    }
}
