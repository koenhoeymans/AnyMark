<?php

namespace AnyMark\Api;

interface Pattern
{
    public function getRegex() : string;

    public function handleMatch(
        array $match,
        \ElementTree\Element $parent = null,
        Pattern $parentPattern = null
    ) : ?\ElementTree\Component;

    public function createElement(string $name) : \ElementTree\Element;

    public function createText(string $text) : \ElementTree\Text;

    public function createComment(string $text) : \ElementTree\Comment;
}
