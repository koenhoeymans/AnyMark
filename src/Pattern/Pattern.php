<?php

namespace AnyMark\Pattern;

/**
 * When a text matches its pattern it transforms it.
 */
abstract class Pattern implements \AnyMark\Api\Pattern
{
    abstract public function getRegex() : string;

    abstract public function handleMatch(
        array $match,
        \ElementTree\Element $parent = null,
        \AnyMark\Api\Pattern $parentPattern = null
    ) : ?\ElementTree\Component;

    public function createElement(string $name) : \ElementTree\Element
    {
        return new \ElementTree\Element($name);
    }

    public function createText(string $text) : \ElementTree\Text
    {
        return new \ElementTree\Text($text);
    }

    public function createComment(string $text) : \ElementTree\Comment
    {
        return new \ElementTree\Comment($text);
    }
}
