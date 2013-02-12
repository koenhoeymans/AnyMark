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
class Italic extends Pattern
{
	public function getRegex()
	{
		return
			"@
				(?<=\s|^)
			_
				(?=\S)
			(
				(
					(?!_).
				|
					_(?=\S)
					.*[^_].*
					_(?<=\S)(?!\w)
				|
					(?!_).*_(?!_).*
				)+
			)
				(?<!\s)
			_
				(?!\w)
			@xU";
	}

	public function handleMatch(
		array $match, ComponentTree $parent, Pattern $parentPattern = null
	) {
		$i = $parent->createElement('i');
		$i->append($parent->createText($match[1]));

		return $i;
	}
}