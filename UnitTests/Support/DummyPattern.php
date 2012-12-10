<?php

/**
 * @package AnyMark
 */
namespace AnyMark\UnitTests\Support;

use AnyMark\Pattern\Pattern;

/**
 * @package AnyMark
 */
class DummyPattern extends Pattern
{
	public function getRegex()
	{
		
	}
	
	public function handleMatch(array $match, \DOMNode $parentNode, Pattern $parentPattern = null)
	{
		
	}
}