<?php

namespace AnyMark\UnitTests\Support;

abstract class PatternReplacementAssertions extends \PHPUnit_Framework_TestCase
{
	abstract protected function getPattern();

	protected function assertDoesNotCreateDomFromText($text)
	{
		$this->assertTrue($this->domOrTextFrom($text, new \DOMDocument()) === $text);
	}

	protected function assertCreatesDomFromText(\DOMNode $dom, $text)
	{
		if($dom instanceof \DOMDocument)
		{
			$docA = $dom;
		}
		elseif (!$dom->ownerDocument)
		{
			$docA = new \DOMDocument();
			$docA->appendChild($dom);
		}
		else
		{
			$docA = $dom->ownerDocument;
		}
		$docB = new \DOMDocument();
		$dom = $this->domOrTextFrom($text, $docB);
		if (is_string($dom) || !$dom)
		{
			$this->fail('no match generated by pattern or string return type');
		}
		$docB->appendChild($dom);

		$this->assertEquals($docA->saveXML(), $docB->saveXML());
	}

	private function domOrTextFrom($text, \DOMDocument $domDocument)
	{
		preg_match($this->getPattern()->getRegex(), $text, $match);
		if ($match) {
			$handledMatch = $this->getPattern()->handleMatch($match, $domDocument);
			# if pattern decides the match is no real match
			if (!$handledMatch)
			{
				return $text;
			}
			return $handledMatch;
		}
		else {
			return $text;
		}
	}
}