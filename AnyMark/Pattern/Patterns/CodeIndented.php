<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;
use ElementTree\ElementTree;

/**
 * @package AnyMark
 */
class CodeIndented extends Code
{
	public function getRegex()
	{
		return
			'@
			(?<=^|\n\n|(?<newline>^\n))
			(?<code>
			(\t|[ ]{4}).*
			(\n+(\t|[ ]{4}).*)*
			)
			(?=\n\n|\n$|$)
			@x';
	}

	public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
		if ($parentPattern && $match['newline'] === "\n")
		{
			if ($parentPattern instanceof \AnyMark\Pattern\Patterns\ManualHtml)
			{
				return false;
			}
		}

		$code = preg_replace("#(\n|^)(\t|[ ]{4})#", "\${1}", $match['code']);
		return $this->createCodeReplacement($code . "\n", true, $parent);
	}
}