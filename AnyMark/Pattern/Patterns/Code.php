<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;

/**
 * @package AnyMark
 */
abstract class Code extends Pattern
{
	protected function createCodeReplacement($code, $pre = true, \DOMNode $parentNode)
	{
		$ownerDocument = $this->getOwnerDocument($parentNode);
		$codeDom = $ownerDocument->createElement('code');
		$codeDom->appendChild($ownerDocument->createTextNode($code));

		if ($pre)
		{
			$preDom = $ownerDocument->createElement('pre');
			$preDom->appendChild($codeDom);

			return $preDom;
		}
		else
		{
			return $codeDom;
		}
	}
}