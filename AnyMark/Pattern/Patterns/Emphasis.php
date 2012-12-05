<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;

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

	public function handleMatch(array $match, \DOMNode $parentNode, Pattern $parentPattern = null)
	{
		$ownerDocument = $this->getOwnerDocument($parentNode);
		$em = $ownerDocument->createElement('em');
		$em->appendChild($ownerDocument->createTextNode($match['text']));

		return $em;
	}
}