<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Processor\Processors;

use AnyMark\Processor\DomProcessor;

/**
 * @package AnyMark
 */
class EmailObfuscator implements DomProcessor
{
	public function process(\DOMDocument $domDocument)
	{
		$xpath = new \DOMXPath($domDocument);
		
		$nodes = $xpath->query('//*');
		foreach ($nodes as $node)
		{
			foreach ($node->attributes as $attr)
			{
				if ($attr->ownerElement->nodeName !== 'a')
				{
					continue;
				}
		
				if(substr($attr->value, 0, 7) !== 'mailto:')
				{
					continue;
				}

				$email = $attr->value;
				$anchorText = $attr->ownerElement->nodeValue;

				$attr->value = '';
				$attr->ownerElement->nodeValue = '';

				# first the link itself
				$emailChars = $this->encode($email);
				$encodedEmail = implode('', $emailChars);
				$attr->appendChild($attr->ownerDocument->createTextNode($encodedEmail));

				# next the anchor text of the a element
				if ($email ===  'mailto:' . $anchorText)
				{
					$anchorTextChars = array_slice($emailChars, 7);
				}
				else
				{
					$anchorTextChars = $this->encode($anchorText);
				}
				$anchorText = implode('', $anchorTextChars);
				$attr->ownerElement->appendChild(
					$attr->ownerDocument->createTextNode($anchorText)
				);
			}
		}

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