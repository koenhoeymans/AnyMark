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
class HorizontalRule extends Pattern
{
    public function getRegex()
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
    ) {
        return $this->createElement('hr');
    }
}
