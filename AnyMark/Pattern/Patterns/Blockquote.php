<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;

/**
 * @package AnyMark
 */
class Blockquote extends Pattern
{
	public function getRegex()
	{
		return
			'@
			(?<=^|\n\n)
			(?<quote>
				[ ]{0,3}			# indentation
				>.+					# followed by > and the quoted text
				(\n.+)*				# following text on following line, < not
									# required anymore
			)
			(?=\n\n|$)
			@x';
	}

	public function handleMatch(array $match, \DOMNode $parentNode, Pattern $parentPattern = null)
	{
		$ownerDocument = $this->getOwnerDocument($parentNode);
		$text = preg_replace("#(^|\n)> ?#", "\${1}", $match['quote']);
		$blockquote = $ownerDocument->createElement('blockquote', $text . "\n\n");

		return $blockquote;
	}
}