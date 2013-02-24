<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Processor\Processors;

use AnyMark\Processor\ElementTreeProcessor;
use ElementTree\Element;
use ElementTree\ElementTree;
use ElementTree\Component;

/**
 * @package AnyMark
 */
class EmailObfuscator implements ElementTreeProcessor
{
	public function process(ElementTree $elementTree)
	{
		$callback = function(Component $component)
		{
			if ($component->getName() !== 'a')
			{
				return;
			}
			$mailto = $component->getAttributeValue('href');
			if (!$mailto || substr($mailto, 0, 7) !== 'mailto:')
			{
				return;
			}
			$mailto = implode('', $this->encode('mailto:' . $mailto));
			$component->setAttribute('href', $mailto);
			$child = $component->getChildren()[0];
			$anchor = implode('', $this->encode($child->getValue()));
			$text = $component->createText($anchor);
			$component->remove($child);
			$component->append($text);
		};

		$elementTree->query($elementTree->createFilter($callback)->allElements());
	}

	private function encode($text)
	{
		// based on/mostly copied from PHPMarkdowns Implementation
		$chars = preg_split('/(?<!^)(?!$)/', $text);
		$seed = (int)abs(crc32($text) / strlen($text)); # Deterministic seed.

		foreach ($chars as $key => $char) {
			$ord = ord($char);
			# Ignore non-ascii chars.
			if ($ord < 128) {
				$r = ($seed * (1 + $key)) % 100; # Pseudo-random function.
				# roughly 10% raw, 45% hex, 45% dec
				# '@' *must* be encoded. I insist.
				if ($r > 90 && $char != '@') /* do nothing */;
				else if ($r < 45) $chars[$key] = '&#x'.dechex($ord).';';
				else              $chars[$key] = '&#'.$ord.';';
			}
		}

		return $chars;
	}
}