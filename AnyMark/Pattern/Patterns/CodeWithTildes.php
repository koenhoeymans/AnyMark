<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;

/**
 * @package AnyMark
 */
class CodeWithTildes extends Code
{
	public function getRegex()
	{
		return "#(?<=\n\n)(\s*)~~~.*\n+(\\1\s*)((\n|.)+?)\n+\\1~~~.*(?=\n\n)#";
	}

	public function handleMatch(array $match, \DOMNode $parentNode, Pattern $parentPattern = null)
	{
		$code = preg_replace("#\n$match[2](\s*.+)#", "\n\${1}", $match[3]);
		return $this->createCodeReplacement($code, true, $parentNode);
	}
}