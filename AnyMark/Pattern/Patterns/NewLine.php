<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;

/**
 * @package AnyMark
 */
class NewLine extends Pattern
{
	public function getRegex()
	{
		return "@[ ][ ](?=\n)@";
	}

	public function handleMatch(array $match, \DOMNode $parentNode, Pattern $parentPattern = null)
	{
		return $this->getOwnerDocument($parentNode)->createElement('br');
	}
}