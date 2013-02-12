<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;
use AnyMark\ComponentTree\ComponentTree;

/**
 * @package AnyMark
 */
class HorizontalRule extends Pattern
{
	public function getRegex()
	{
		return
		'@
		(?<=\n)
		([ ]{0,3}(?<marker>-|\*|_))
		([ ]{0,3}\g{marker}){2,}
		(?=\n)
		@x';
	}

	public function handleMatch(
		array $match, ComponentTree $parent, Pattern $parentPattern = null
	) {
		return $parent->createElement('hr');
	}
}