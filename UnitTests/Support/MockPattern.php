<?php

namespace AnyMark\UnitTests\Support;

use \AnyMark\Pattern\Pattern;
use \AnyMark\ComponentTree\ComponentTree;

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

	public function getRegex()
	{
		return $this->regex;
	}

	public function handleMatch(
		array $match, ComponentTree $parent, Pattern $parentPattern = null
	) {
		$element = $parent->createElement($this->elementName);
		$text = $parent->createText($this->textInElement);
		$element->append($text);

		return $element;
	}
}