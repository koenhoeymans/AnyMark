<?php

namespace AnyMark\Pattern;

use \ElementTree\Element;
use \ElementTree\Text;
use \ElementTree\Comment;
use \ElementTree\Component;
use \AnyMark\Api\Pattern as PatternApi;

/**
 * When a text matches its pattern it transforms it.
 */
abstract class Pattern implements PatternApi
{
    abstract public function getRegex() : string;

    abstract public function handleMatch(
        array $match,
        Element $parent = null,
        PatternApi $parentPattern = null
    ) : ?Component;

    public function createElement(string $name) : Element
    {
        return new Element($name);
    }

    public function createText(string $text) : Text
    {
        return new Text($text);
    }

    public function createComment(string $text) : Comment
    {
        return new Comment($text);
    }
}
