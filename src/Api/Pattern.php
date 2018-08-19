<?php

namespace AnyMark\Api;

use ElementTree\Comment;
use ElementTree\Component;
use ElementTree\Element;
use ElementTree\Text;

interface Pattern
{
    public function getRegex(): string;

    public function handleMatch(
        array $match,
        Element $parent = null,
        Pattern $parentPattern = null
    ): ?Component;

    public function createElement(string $name): Element;

    public function createText(string $text): Text;

    public function createComment(string $text): Comment;
}
