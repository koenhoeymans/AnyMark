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

	public function createItalic($text)
	{
		$i = new \AnyMark\ElementTree\Element('i');
		$text = new \AnyMark\ElementTree\Text($text);
		$i->append($text);

		return $i;
	}

	/**
	 * @test
	 */
	public function italicTextIsPlacedBetweenUnderscores()
	{
		$text = "This is a sentence with _italicized_ text.";
		$i = $this->createItalic('italicized');
		$this->assertEquals($i, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function italicTextCanContainMultipleWords()
	{
		$text = "This is a sentence with _italicized text_.";
		$i = $this->createItalic('italicized text');
		$this->assertEquals($i, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function textCanContainMultipleItalicSections()
	{
		$text = "This is _a sentence_ with _italicized text_.";
		$i = $this->createItalic('a sentence');
		$this->assertEquals($i, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function aWordCannotContainAnItalicizedPart()
	{
		$text = "This word is not _ita_licized.";
		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function firstUnderScoreMustBePrecededBySpace()
	{
		$text = "This is not an_italicized_ word.";
		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function theFirstUnderscoreCannotHaveSpaceAfterIt()
	{
		$text = "This is not a sentence with _ italicized_ text.";
		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function theLastUnderscoreCannotHaveSpaceBeforeIt()
	{
		$text = "This is not a sentence with _italicized _ text.";
		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function onlyUnderscoresAreNotItalicized()
	{
		$text = "This is not a sentence with _____.";
		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canContainStrongText()
	{
		$text = "_emphasized with __strong___ text.";
		$i = $this->createItalic('emphasized with __strong__');
		$this->assertEquals($i, $this->applyPattern($text));
	}
	
	/**
	 * @test
	 */
	public function canContainStrongText2()
	{
		$text = "___emphasized__ with strong_ text.";
		$i = $this->createItalic('__emphasized__ with strong');
		$this->assertEquals($i, $this->applyPattern($text));
	}
}