<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Processor\Processors;

use AnyMark\Processor\TextProcessor;

/**
 * @package AnyMark
 */
class NewLineStandardizer implements TextProcessor
{
	public function process($text)
	{
		return preg_replace("#\r\n?#", "\n", $text);
	}
}