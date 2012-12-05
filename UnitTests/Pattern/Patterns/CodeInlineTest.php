<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Pattern_Patterns_CodeInlineTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->codeInline = new \AnyMark\Pattern\Patterns\CodeInline();
	}

	public function getPattern()
	{
		return $this->codeInline;
	}

	/**
	 * @test
	 */
	public function transformsCodeBetweenBackticks()
	{
		$text = 'Text with `code` in between.';
		$dom = new \DOMElement('code', 'code');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function canStartAndEndWithMultipleBackticks()
	{
		$text = 'Text with ``code`` in between.';
		$dom = new \DOMElement('code', 'code');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function backtickCanBePlacedWithinMultipleBackticks()
	{
		$text = 'Text with ``co`de`` in between.';
		$dom = new \DOMElement('code', 'co`de');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function takesTheMostOutwardBackticks()
	{
		$text = 'Text with ``code``` in between.';
		$dom = new \DOMElement('code', 'code`');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function backslashEscapes()
	{
		$text = 'Code \`that` is escaped.';
		$this->assertDoesNotCreateDomFromText($text);
	}

	/**
	 * @test
	 */
	public function backticksCanBeFollowedByNonSpace()
	{
		$text = 'This `code`: look below.';
		$dom = new \DOMElement('code', 'code');
		$this->assertCreatesDomFromText($dom, $text);
	}
}