<?php

/**
 * @package AnyMark
 */
namespace AnyMark\UnitTests\Support;

use AnyMark\Pattern\Pattern;
use ElementTree\ElementTree;

/**
 * @package AnyMark
 */
class DummyPattern extends Pattern
{
	public function getRegex()
	{
		
	}
	
	public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {}
}