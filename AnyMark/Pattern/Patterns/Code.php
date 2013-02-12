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
abstract class Code extends Pattern
{
	protected function createCodeReplacement($code, $pre = true, ComponentTree $parent)
	{
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