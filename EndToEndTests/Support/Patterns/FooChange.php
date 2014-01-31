<?php

/**
 * @package AnyMark
 */
namespace AnyMark\EndToEndTests\Support\Patterns;

use AnyMark\Pattern\Pattern;
use ElementTree\Composable;

/**
 * @package AnyMark
 */
class FooChange extends \AnyMark\Pattern\Pattern
{
	public function getRegex()
	{
		return '@foo@';
	}
	
	public function handleMatch(
		array $match, Composable $parent, Pattern $parentPattern = null
	) {
		return $this->createText('bar');
	}
}