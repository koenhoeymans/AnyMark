<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Processor\Processors;

use AnyMark\Processor\TextProcessor;

/**
 * @package AnyMark
 */
class EmptyLineFixer implements TextProcessor
{
	public function process($text)
	{
		return preg_replace("#\n[\t ]+\n#", "\n\n", $text);
	}
}