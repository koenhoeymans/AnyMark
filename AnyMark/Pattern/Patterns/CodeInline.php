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
class CodeInline extends Code
{
	public function getRegex()
	{
		return
			'@
			(?<!\\\)[`](?<extra_backticks>([`])*)
			(?<code>.+?)
			\g{extra_backticks}[`](?!`)
			@x';
	}

	public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
		# if code between backticks starts or ends with code between
		# backticks: remove the spacing
		$code = preg_replace("#^\s*(.+?)\s*$#", "\${1}", $match['code']);
		
		return $this->createCodeReplacement($code, false, $parent);
	}
}