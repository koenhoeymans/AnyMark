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
abstract class Code extends Pattern
{
	protected function createCodeReplacement($code, $pre = true, ElementTree $parent)
	{
		$code = htmlentities($code, ENT_NOQUOTES);
		$codeElement = $parent->createElement('code');
		$codeElement->append($parent->createText($code));

		if ($pre)
		{
			$preElement = $parent->createElement('pre');
			$preElement->append($codeElement);

			return $preElement;
		}
		else
		{
			return $codeElement;
		}
	}
}