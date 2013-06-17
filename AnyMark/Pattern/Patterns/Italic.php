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
class Italic extends Pattern
{
	public function getRegex()
	{
		return
			'@
			(?<=\s|^)
			_
			(?=\S)
			(?(?=(?<triple>__))				# cfr emphasis
				(?=__)
				|
				(?(?=(?<strong>_))(?=_)))
			(?!__[^_]+___)
			(?(?=__)(?!__[^_]+_\s))
			(?=_*[^_]+_)
				(?<text>
					(__[^_]+__)?
					(
					__(?=\S)
					|
					__(?=\S).+(?<=\S)__
					|
					(\s_.+_\s)
					|
					_[^_]
					|
					[^_]
					)+?
				)
			(?<=\S)
			(?(?=\g{strong})_+)
			_
			(?=\s|[^_\w]|$)
			@x';
	}

	public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
		if (substr($match[0], 0, 2) === '__' && substr($match[0], -2) === '__')
		{
			return;
		}

		$i = $parent->createElement('i');
		$i->append($parent->createText($match['text']));

		return $i;
	}
}