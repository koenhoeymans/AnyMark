<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;

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

	public function handleMatch(array $match, \DOMNode $parentNode, Pattern $parentPattern = null)
	{
		$ownerDocument = $this->getOwnerDocument($parentNode);
		$i = $ownerDocument->createElement('i');
		$i->appendChild($ownerDocument->createTextNode($match[1]));

		return $i;
	}
}