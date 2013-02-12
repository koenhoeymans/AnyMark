<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern;

use AnyMark\ComponentTree\ComponentTree;

/**
 * @package vidola
 * 
 * When a text matches its pattern it transforms it.
 */
abstract class Pattern
{
	abstract public function getRegex();

	abstract public function handleMatch(
		array $match, ComponentTree $parent, Pattern $parentPattern = null
	);
}