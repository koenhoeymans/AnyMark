<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern;

/**
 * @package vidola
 * 
 * When a text matches its pattern it transforms it.
 */
abstract class Pattern
{
	abstract public function getRegex();

	abstract public function handleMatch(array $match, \DOMNode $parentNode, Pattern $parentPattern = null);

	protected function getOwnerDocument(\DOMNode $node)
	{
		return ($node->ownerDocument) ?: $node;
	}
}