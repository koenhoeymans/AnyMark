<?php

namespace AnyMark\UnitTests\Support;

use \AnyMark\Pattern\Pattern;

class MockPatternCreatingMultiNodes extends \AnyMark\Pattern\Pattern
{
	private $regex;

	private $elementName;

	private $subElements;

	public function __construct($regex, $elementName, $textInElement, array $subElement1)
	{
		$arguments = func_get_args();
		$this->regex = array_shift($arguments);
		$this->elementName = array_shift($arguments);
		$this->subElements = $arguments;
	}

	public function getRegex()
	{
		return $this->regex;
	}

	public function handleMatch(array $match, \DOMNode $parentNode, Pattern $parentPattern = null)
	{
		$domDocument = $this->getOwnerDocument($parentNode);
		$el = $domDocument->createElement($this->elementName);
		foreach ($this->subElements as $subElement)
		{
			$subEl = $domDocument->createElement($subElement['tag'], $subElement['text']);
			$el->appendChild($subEl);
		}
		return $el;
	}
}