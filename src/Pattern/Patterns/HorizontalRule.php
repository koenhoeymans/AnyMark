<?php

namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;
use ElementTree\Element;

class HorizontalRule extends Pattern
{
    public function getRegex() : string
    {
        return
        '@
		(?<=\n)
		([ ]{0,3}(?<marker>-|\*|_))
		([ ]{0,3}\g{marker}){2,}
		(\t|[ ])*
		(?=\n)
		@x';
    }

    public function handleMatch(
        array $match,
        Element $parent = null,
        Pattern $parentPattern = null
    ) : Element {
        return $this->createElement('hr');
    }
}
