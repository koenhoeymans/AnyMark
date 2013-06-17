<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;
use ElementTree\ElementTree;

/**
 * @package AnyMark
 */
class Emphasis extends Pattern
{
	public function getRegex()
	{
		return
		'@
			(?<=^|\s)
			[*]
			(?=\S)
				(
					(?R)
					|
					[^*]
					|
					([*]([^*]|(?2))+?(?<=\S)[*])
				)+?
			(?<=\S)
			[*]
			(?![a-zA-Z0-9*])
		@x';
	}

	public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
		if (substr($match[0], 0, 2) === '**' && substr($match[0], -2) === '**')
		{
			return;
		}

		$em = $parent->createElement('em');
		$em->append($parent->createText(substr($match[0], 1, -1)));

		return $em;
	}
}