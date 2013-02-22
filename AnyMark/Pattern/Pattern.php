<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern;

use ElementTree\ElementTree;

/**
 * @package vidola
 * 
 * When a text matches its pattern it transforms it.
 */
abstract class Pattern
{
	abstract public function getRegex();

	abstract public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	);
}