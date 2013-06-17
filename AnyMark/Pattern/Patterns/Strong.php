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
class Strong extends Pattern
{
	public function getRegex()
	{
		return
		'@
			(?<=^|\s)
			(?<marker>[_*])
			\g{marker}
			(?=\S)
				(
					(?R)
					|
					(?!\g{marker}).
					|
					\g{marker}(?!\g{marker}).+?(?<=\S)\g{marker}
					|
					\g{marker}\g{marker}(?!\g{marker}).+?(?<=\S)\g{marker}\g{marker}
				)+?
			(?<=\S)
			\g{marker}
			\g{marker}
			(?![a-zA-Z0-9])
			(?!\g{marker})
		@x';
	}

	public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
		$marker = $match['marker'] . $match['marker'];
		if (substr($match[0], 0, 2) !== $marker || substr($match[0], -2) !== $marker)
		{
			return;
		}

		$strong = $parent->createElement('strong');
		$strong->append($parent->createText(substr($match[0], 2, -2)));

		return $strong;
	}
}