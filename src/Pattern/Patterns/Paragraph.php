<?php

namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;
use ElementTree\Element;

class Paragraph extends Pattern
{
    public function getRegex(): string
    {
        return
            '@
			(
				(?<=^|^\n|\n\n)
				(?<text>
					(?<indent>[ ]{0,3})\S.*
					(\n.+)*
				)
				(?=(\n\g{indent}\s.*)*\n\n) # para possible with list on next line
			|
				(?J)
				(?<=\n\n)
				(?<text>
					(?<indent>[ ]{0,3})\S.*
					(\n.+)*
				)
				(?=(\n\g{indent}\s.*)*\n\n|\n$|$)
			)
			@x';
    }

    public function handleMatch(
        array $match,
        Element $parent = null,
        \AnyMark\Api\Pattern $parentPattern = null
    ): ?\ElementTree\Component {
        $p = $this->createElement('p');
        $p->append($this->createText($match['text']));

        return $p;
    }
}
