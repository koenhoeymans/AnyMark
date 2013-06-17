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
class Blockquote extends Pattern
{
	public function getRegex()
	{
		return
			'@
			(?<=^|\n)
			(?<quote>
				[ ]{0,3}			# indentation
				>.+					# followed by > and the quoted text
				(\n.+)*				# following text on following line, < not
									# required anymore
			)
			(?=\n\n|$)
			@x';
	}

	public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
		$text = preg_replace("#(^|\n)> ?#", "\${1}", $match['quote']);
		$blockquote = $parent->createElement('blockquote');
		$blockquote->append($parent->createText($text . "\n\n"));

		return $blockquote;
	}
}