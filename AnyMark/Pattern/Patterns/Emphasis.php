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
			(?<!\\\)
			(

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
			(?<!\\\)
			[*]

			|

			[_]
			(?=\S)
				(
					(?R)
					|
					[^_]
					|
					([_]([^_]|(?6))+?(?<=\S)[_])
				)+?
			(?<=\S)
			(?<!\\\)
			[_]
	
			)
		@x';
	}

	public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
		if (substr($match[0], 0, 2) === '**' && substr($match[0], -2) === '**')
		{
			return;
		}
		if (substr($match[0], 0, 2) === '__' && substr($match[0], -2) === '__')
		{
			return;
		}

		$em = $parent->createElement('em');
		$em->append($parent->createText(substr($match[0], 1, -1)));

		return $em;
	}
}