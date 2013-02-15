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
class Emphasis extends Pattern
{
	public function getRegex()
	{
		return
			'@
			(?<=\s|^)
			\*
				(?![ ])
				(?<text>
					(?(?=\*)
						(\*\*|\*(?<=\S)(?=\S)|\*(?<=\s)(?=\s))
						|
						.
					)*?
				)
			(?<=\S)
			\*
			(?!\w|\*)
			@x';
	}

	public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
		$em = $parent->createElement('em');
		$em->append($parent->createText($match['text']));

		return $em;
	}
}