<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Plugins;

use ElementTree\ElementTree;
use ElementTree\Component;
use AnyMark\Events\AfterParsing;
use Epa\EventMapper;
use Epa\Plugin;

/**
 * @package AnyMark
 */
class EmailObfuscator implements Plugin
{
	public function register(EventMapper $mapper)
	{
		$mapper->registerForEvent(
			'AnyMark\\Events\\AfterParsing', function(AfterParsing $event) {
				$this->handleTree($event->getTree());
			}
		);
	}

	private function handleTree(ElementTree $tree)
	{
		$callback = function(Component $component)
		{
			if ($component->getName() !== 'a')
			{
				return;
			}
			$mailto = $component->getAttributeValue('href');

			if (empty($mailto) || substr($mailto, 0, 7) !== 'mailto:')
			{
				return;
			}

			$mailto = implode('', $this->encode(substr($mailto, 7)));
			$component->setAttribute('href', $mailto);

			$child = $component->getChildren()[0];
			$anchor = implode('', array_slice($this->encode($child->getValue()), 7));
			$text = $component->createText($anchor);
			$component->remove($child);
			$component->append($text);
		};

		$tree->query($tree->createFilter($callback)->allElements());
	}

	private function encode($addr)
	{
		// based on/copied from PHPMarkdowns Implementation
		$addr = 'mailto:' . $addr;
		$chars = preg_split('/(?<!^)(?!$)/', $addr);
		$seed = (int)abs(crc32($addr) / strlen($addr)); # Deterministic seed.

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