<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;
use ElementTree\Composable;

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
		(\t|[ ])*
		(?=\n)
		@x';
	}

	public function handleMatch(
		array $match, Composable $parent, Pattern $parentPattern = null
	) {
		return $this->createElement('hr');
	}
}