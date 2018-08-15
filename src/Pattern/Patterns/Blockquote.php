<?php

namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;
use ElementTree\Element;

class Blockquote extends Pattern
{
    public function getRegex() : string
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
    ) : Element {
        $text = preg_replace("#(^|\n)> ?#", "\${1}", $match['quote']);
        $blockquote = $this->createElement('blockquote');
        $blockquote->append($this->createText($text."\n\n"));

        return $blockquote;
    }
}
