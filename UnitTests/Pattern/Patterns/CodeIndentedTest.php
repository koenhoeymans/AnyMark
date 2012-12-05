<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Pattern_Patterns_CodeIndentedTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->pattern = new \AnyMark\Pattern\Patterns\CodeIndented();
	}

	public function getPattern()
	{
		return $this->pattern;
	}

	public function createDomFromText($text)
	{
		$domDoc = new \DOMDocument();
		$domElementCode = new \DOMElement('code', $text);
		$domElementPre = new \DOMElement('pre');
		$domDoc->appendChild($domElementPre);
		$domElementPre->appendChild($domElementCode);
		return $domElementPre;
	}

	/**
	 * @test
	 */
	public function indentedTextIsAlsoCode()
	{
		$text =
"paragraph

	code

paragraph";

		$this->assertCreatesDomFromText($this->createDomFromText('code'), $text);
	}

	/**
	 * @test
	 */
	public function variableIndentationIsPossibleWithinCode()
	{
		$text =
"paragraph

		a
	b
		c

paragraph";
		
		$codeText =
"	a
b
	c";
		
		$this->assertCreatesDomFromText($this->createDomFromText($codeText), $text);
	}

	/**
	 * @test
	 */
	public function onlyBlankLinesBeforeAndAfterInStringAreSufficient()
	{
		$text =
"

	code

";
		
		$this->assertCreatesDomFromText($this->createDomFromText('code'), $text);		
	}

	/**
	 * @test
	 */
	public function codeCanContainBlankLines()
	{
		$text =
"paragraph

	code

	continued

paragraph";

		$this->assertCreatesDomFromText($this->createDomFromText("code\n\ncontinued"), $text);
	}
}