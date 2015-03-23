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
        array $match,
        Element $parent = null,
        Pattern $parentPattern = null
    ) {
        $text = preg_replace("#(^|\n)> ?#", "\${1}", $match['quote']);
        $blockquote = $this->createElement('blockquote');
        $blockquote->append($this->createText($text."\n\n"));

        return $blockquote;
    }
}
