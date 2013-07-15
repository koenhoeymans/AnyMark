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
	/**
	 * @return string
	 */
	abstract public function getRegex();

	/**
	 * @param array $match
	 * @param ElementTree $parent
	 * @param Pattern $parentPattern
	 * @return \ElementTree\Component
	 */
	abstract public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	);
}