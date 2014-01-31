<?php

namespace AnyMark\UnitTests\Support;

use \AnyMark\Pattern\Pattern;
use ElementTree\Composable;

class MockPatternCreatingMultiNodes extends \AnyMark\Pattern\Pattern
{
	private $regex;

	private $elementName;

	private $subElements;

	public function __construct($regex, $elementName, array $subElement1)
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

	public function handleMatch(
		array $match, Composable $parent, Pattern $parentPattern = null
	) {
		$el = $this->createElement($this->elementName);
		foreach ($this->subElements as $subElement)
		{
			$subEl = $this->createElement($subElement['tag']);
			$subEl->append($this->createText($subElement['text']));
			$el->append($subEl);
		}
		return $el;
	}
}