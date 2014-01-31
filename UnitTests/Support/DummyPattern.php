<?php

/**
 * @package AnyMark
 */
namespace AnyMark\UnitTests\Support;

use AnyMark\Pattern\Pattern;
use ElementTree\Composable;

/**
 * @package AnyMark
 */
class DummyPattern extends Pattern
{
	public function getRegex()
	{}
	
	public function handleMatch(
		array $match, Composable $parent, Pattern $parentPattern = null
	) {}
}