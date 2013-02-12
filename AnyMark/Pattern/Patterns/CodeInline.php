<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;
use AnyMark\ComponentTree\ComponentTree;

/**
 * @package AnyMark
 */
class CodeInline extends Code
{
	public function getRegex()
	{
		return
			'@
			(?<=^|\s)[`](?<extra_backticks>([`])*)
			(?<code>.+?)
			\g{extra_backticks}[`](?!`)
			@x';
	}

	public function handleMatch(
		array $match, ComponentTree $parent, Pattern $parentPattern = null
	) {
		# if code between backticks starts or ends with code between
		# backticks: remove the spacing
		$code = preg_replace("#^\s*(.+?)\s*$#", "\${1}", $match['code']);
		
		return $this->createCodeReplacement($code, false, $parent);
	}
}