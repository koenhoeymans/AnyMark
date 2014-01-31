<?php

namespace AnyMark\UnitTests\Support;

use \AnyMark\Pattern\Pattern;
use ElementTree\Composable;

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
		array $match, Composable $parent, Pattern $parentPattern = null
	) {
		$element = $this->createElement($this->elementName);
		$text = $this->createText($this->textInElement);
		$element->append($text);

		return $element;
	}
}