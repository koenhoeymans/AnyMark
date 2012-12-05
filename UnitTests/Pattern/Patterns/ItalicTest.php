<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Pattern_Patterns_ItalicTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->italic = new \AnyMark\Pattern\Patterns\Italic();
	}

	protected function getPattern()
	{
		return $this->italic;
	}

	/**
	 * @test
	 */
	public function italicTextIsPlacedBetweenUnderscores()
	{
		$text = "This is a sentence with _italicized_ text.";
		$dom = new \DOMElement('i', 'italicized');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function italicTextCanContainMultipleWords()
	{
		$text = "This is a sentence with _italicized text_.";
		$dom = new \DOMElement('i', 'italicized text');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function textCanContainMultipleItalicSections()
	{
		$text = "This is _a sentence_ with _italicized text_.";
		$dom = new \DOMElement('i', 'a sentence');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function aWordCannotContainAnItalicizedPart()
	{
		$text = "This word is not _ita_licized.";
		$this->assertDoesNotCreateDomFromText($text);
	}

	/**
	 * @test
	 */
	public function firstUnderScoreMustBePrecededBySpace()
	{
		$text = "This is not an_italicized_ word.";
		$this->assertDoesNotCreateDomFromText($text);
	}

	/**
	 * @test
	 */
	public function theFirstUnderscoreCannotHaveSpaceAfterIt()
	{
		$text = "This is not a sentence with _ italicized_ text.";
		$this->assertDoesNotCreateDomFromText($text);
	}

	/**
	 * @test
	 */
	public function theLastUnderscoreCannotHaveSpaceBeforeIt()
	{
		$text = "This is not a sentence with _italicized _ text.";
		$this->assertDoesNotCreateDomFromText($text);
	}

	/**
	 * @test
	 */
	public function onlyUnderscoresAreNotItalicized()
	{
		$text = "This is not a sentence with _____.";
		$this->assertDoesNotCreateDomFromText($text);
	}

	/**
	 * @test
	 */
	public function canContainStrongText()
	{
		$text = "_emphasized with __strong___ text.";
		$dom = new \DOMElement('i', 'emphasized with __strong__');
		$this->assertCreatesDomFromText($dom, $text);
	}
	
	/**
	 * @test
	 */
	public function canContainStrongText2()
	{
		$text = "___emphasized__ with strong_ text.";
		$dom = new \DOMElement('i', '__emphasized__ with strong');
		$this->assertCreatesDomFromText($dom, $text);
	}
}