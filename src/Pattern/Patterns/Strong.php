<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;
use ElementTree\Element;

/**
 * @package AnyMark
 */
class Strong extends Pattern
{
	public function getRegex()
	{
		return
		'@
			(?<![_*])
			(?<!\\\)
			(?<marker>[_*])
			\g{marker}
			(?=\S)
				(
					(?R)
					|
					(?!\g{marker}).
					|
					\g{marker}(?!\g{marker}).+?(?<=\S)\g{marker}
				)+?
			(?<=\S)
			(?<!\\\)
			\g{marker}
			\g{marker}
			(?!\w+\g{marker}\g{marker})
		@x';
	}

	public function handleMatch(
		array $match, Element $parent = null, Pattern $parentPattern = null
	) {
		$marker = $match['marker'] . $match['marker'];
		if (substr($match[0], 0, 2) !== $marker || substr($match[0], -2) !== $marker)
		{
			return;
		}
		if (substr($match[0], 0, 4) === '____' && substr($match[0], -4) === '____')
		{
			return;
		}

		$strong = $this->createElement('strong');
		$strong->append($this->createText(substr($match[0], 2, -2)));

		return $strong;
	}
}