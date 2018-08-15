<?php

namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;
use ElementTree\Element;

class AutoLink extends Pattern
{
    public function getRegex() : string
    {
        return
            '@

			<(?<mail>	# copied from PHPMarkdown
				(?:
					[-!#$%&\'*+/=?^_`.{|}~\w\x80-\xFF]+
				|
					".*?"
				)
				\@
				(?:
					[-a-z0-9\x80-\xFF]+(\.[-a-z0-9\x80-\xFF]+)*\.[a-z]+
				|
					\[[\d.a-fA-F:]+\]	# IPv4 & IPv6
				)
			)>

			|

			<(?<url>
			http://
			[A-Z0-9.-]+\.[A-Z]{2,4}
			(\S+)?
			)>

			@xi';
    }

    public function handleMatch(
        array $match,
        Element $parent = null,
        Pattern $parentPattern = null
    ) : Element {
        $a = $this->createElement('a');
        if (isset($match['url'])) {
            $a->append($this->createText($match['url']));
            $a->setAttribute('href', $match['url']);
        } else {
            $a->append($this->createText($match['mail']));
            $a->setAttribute('href', 'mailto:'.$match['mail']);
        }

        return $a;
    }
}
