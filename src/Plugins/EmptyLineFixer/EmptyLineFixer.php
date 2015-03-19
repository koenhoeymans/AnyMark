<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Plugins\EmptyLineFixer;

/**
 * @package AnyMark
 */
class EmptyLineFixer
{
	public function fix($text)
	{
		return preg_replace("#\n[\t ]+\n#", "\n\n", $text);
	}
}