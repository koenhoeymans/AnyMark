<?php

namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;

class Blockquote extends Pattern
{
    public function getRegex(): string
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
        \ElementTree\Element $parent = null,
        \AnyMark\Api\Pattern $parentPattern = null
    ): ?\ElementTree\Component {
        $text = preg_replace("#(^|\n)> ?#", "\${1}", $match['quote']);
        $blockquote = $this->createElement('blockquote');
        $blockquote->append($this->createText($text . "\n\n"));

        return $blockquote;
    }
}
