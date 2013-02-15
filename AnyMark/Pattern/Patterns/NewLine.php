<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;
use AnyMark\ElementTree\ElementTree;

/**
 * @package AnyMark
 */
class NewLine extends Pattern
{
	public function getRegex()
	{
		return "@[ ][ ](?=\n)@";
	}

	public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
		return $parent->createElement('br');
	}
}