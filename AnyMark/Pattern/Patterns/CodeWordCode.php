<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;

/**
 * @package AnyMark
 */
class CodeWordCode extends Code
{
	public function getRegex()
	{
		return "#(?<=\n\n)(\s+)CODE:\n+(\\1\s+)(.+(\n*\\1\s+.+)*)(?=\n\n|$)#i";
	}

	public function handleMatch(array $match, \DOMNode $parentNode, Pattern $parentPattern = null)
	{
		$code = preg_replace("#\n$match[2](\s*.+)#", "\n\${1}", $match[3]);
		return $this->createCodeReplacement($code, true, $parentNode);
	}
}