<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;
use ElementTree\Composable;

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
		array $match, Composable $parent, Pattern $parentPattern = null
	) {
		return $this->createElement('br');
	}
}