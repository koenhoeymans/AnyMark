<?php

namespace AnyMark;

use AnyMark\Pattern\Pattern;
use ElementTree\Element;

class MockPattern extends \AnyMark\Pattern\Pattern
{
    private $regex;

    private $elementName;

    private $textInElement;

    public function __construct($regex, $elementName, $textInElement)
    {
        $this->regex = $regex;
        $this->elementName = $elementName;
        $this->textInElement = $textInElement;
    }

    public function getRegex(): string
    {
        return $this->regex;
    }

    public function handleMatch(
        array $match,
        Element $parent = null,
        \AnyMark\Api\Pattern $parentPattern = null
    ): ?\ElementTree\Component {
        $element = $this->createElement($this->elementName);
        $text = $this->createText($this->textInElement);
        $element->append($text);

        return $element;
    }
}
