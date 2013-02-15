<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;
use AnyMark\ElementTree\ElementTree;

/**
 * @package AnyMark
 */
class Strong extends Pattern
{
	public function getRegex()
	{
		return
			'@
			(?<=\s|^)

			(?<marker>[_*])
			\g{marker}(?=\S)
			(?<content>
				(
					(?!\g{marker}).
				|
					\g{marker}(?=\S)
					.+
					\g{marker}(?<=\S)(?![a-zA-Z0-9])
				|
					\s\g{marker}\s						# five * three or 5 * 3
				|
					[a-zA-Z0-9]\g{marker}[a-zA-Z0-9]	# five*three or 5*3
				|
					(?!\g{marker}).*\g{marker}(?!\g{marker}).*
				)+
			)
			(?<=\S)
			\g{marker}
			\g{marker}

			(?![a-zA-Z0-9])
			@xU';
	}

	public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
		$strong = $parent->createElement('strong');
		$strong->append($parent->createText($match['content']));

		return $strong;
	}
}