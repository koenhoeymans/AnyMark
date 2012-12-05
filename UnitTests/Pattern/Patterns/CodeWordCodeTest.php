<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Pattern_Patterns_CodeWordCodeTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->pattern = new \AnyMark\Pattern\Patterns\CodeWordCode();
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
	public function codeBlockIsWordCodeCapitalisedAndIndentedFollowedByColon()
	{
		$text = "\n\n\tCODE:\n\t\tthe code\n\n";
		$this->assertCreatesDomFromText($this->createDomFromText('the code'), $text);
	}

	/**
	 * @test
	 */
	public function codeBlocksKeepIndentationAsOutlined()
	{
		$text = "\n\n\tCODE:\n\t\tThis is code.\n\n\t\tThis is also code.\n\t\t\t\tThis line is indented.";
		$this->assertCreatesDomFromText($this->createDomFromText("This is code.\n\nThis is also code.\n\t\tThis line is indented."), $text);
	}

	/**
	 * @test
	 */
	public function codeShouldBeIndentedAfterCodeWord()
	{
		$text = "\n\n\tCODE:\n\tthe code\n\n";
		$this->assertDoesNotCreateDomFromText($text);
	}

	/**
	 * @test
	 */
	public function codeBlockStopsAfterBlanklineWithTextEquallyIndentedWithCodeWord()
	{
		$text =
"

	CODE:
		This is code.

	This line is not code.";
	
		$this->assertCreatesDomFromText($this->createDomFromText('This is code.'), $text);
	}

	/**
	 * @test
	 */
	public function theCodeWordIsCaseInsensitive()
	{
		$text = "\n\n\tcoDe:\n\t\tthe code\n\n";
		$this->assertCreatesDomFromText($this->createDomFromText('the code'), $text);
	}
}