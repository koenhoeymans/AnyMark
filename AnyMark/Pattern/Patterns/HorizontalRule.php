<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;

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

	public function handleMatch(array $match, \DOMNode $parentNode, Pattern $parentPattern = null)
	{
		return $this->getOwnerDocument($parentNode)->createElement('hr');
	}
}