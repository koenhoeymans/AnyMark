<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;
use AnyMark\ElementTree\ElementTree;

/**
 * @package AnyMark
 */
class AutoLink extends Pattern
{
	public function getRegex()
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
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
		$a = $parent->createElement('a');
		if (isset($match['url']))
		{
			$a->append($parent->createText($match['url']));
			$a->setAttribute('href', $match['url']);
		}
		else
		{
			$a->append($parent->createText($match['mail']));
			$a->setAttribute('href', 'mailto:' . $match['mail']);
		}

		return $a;
	}
}