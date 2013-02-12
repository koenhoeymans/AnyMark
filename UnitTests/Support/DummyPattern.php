<?php

/**
 * @package AnyMark
 */
namespace AnyMark\UnitTests\Support;

use AnyMark\Pattern\Pattern;
use AnyMark\ComponentTree\ComponentTree;

/**
 * @package AnyMark
 */
class DummyPattern extends Pattern
{
	public function getRegex()
	{
		
	}
	
	public function handleMatch(
		array $match, ComponentTree $parent, Pattern $parentPattern = null
	) {}
}