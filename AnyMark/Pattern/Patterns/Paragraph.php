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
class Paragraph extends Pattern
{
	public function getRegex()
	{
		return
			'@
			(?|
				(?<=^|^\n|\n\n)
				(?<text>
					(?<indent>[ ]{0,3})\S.*
					(\n(?!\g{indent}\s).+)*
				)
				(?=(\n\g{indent}\s.*)*\n\n) # para possible with list on next line
			|
				(?J)
				(?<=\n\n)
				(?<text>
					(?<indent>[ ]{0,3})\S.*
					(\n(?!\g{indent}\s).+)*
				)
				(?=(\n\g{indent}\s.*)*\n\n|\n$|$)
			)
			@x';
	}

	public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
		$text = preg_replace("@(^|\n)[ ]*@", "\${1}", $match['text']);

		$p = $parent->createElement('p');
		$p->append($parent->createText($text));

		return $p;
	}
}