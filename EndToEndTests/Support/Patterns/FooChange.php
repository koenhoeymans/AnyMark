<?php

/**
 * @package AnyMark
 */
namespace AnyMark\EndToEndTests\Support\Patterns;

use AnyMark\Pattern\Pattern;
use ElementTree\ElementTree;

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
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
		return $parent->createText('bar');
	}
}