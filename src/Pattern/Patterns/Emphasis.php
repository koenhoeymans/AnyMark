<?php

namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;

class Emphasis extends Pattern
{
    public function getRegex(): string
    {
        return
        '@
			(?<!\\\)
			(

			[*]
			(?=\S)
				(
					(?R)
					|
					[^*]
					|
					([*]([^*]|(?2))+?(?<=\S)[*])
				)+?
			(?<=\S)
			(?<!\\\)
			[*]

			|

			[_]
			(?=\S)
				(
					(?R)
					|
					[^_]
					|
					([_]([^_]|(?6))+?(?<=\S)[_])
				)+?
			(?<=\S)
			(?<!\\\)
			[_]

			)
		@x';
    }

    public function handleMatch(
        array $match,
        \ElementTree\Element $parent = null,
        \AnyMark\Api\Pattern $parentPattern = null
    ): ?\ElementTree\Component {
        if (substr($match[0], 0, 2) === '**' && substr($match[0], -2) === '**') {
            return null;
        }
        if (substr($match[0], 0, 2) === '__' && substr($match[0], -2) === '__') {
            return null;
        }

        $em = $this->createElement('em');
        $em->append($this->createText(substr($match[0], 1, -1)));

        return $em;
    }
}
