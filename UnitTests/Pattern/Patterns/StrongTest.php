<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Pattern_Patterns_StrongTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->pattern = new \AnyMark\Pattern\Patterns\Strong();
	}

	protected function getPattern()
	{
		return $this->pattern;
	}

	/**
	 * @test
	 */
	public function strongTextIsPlacedBetweenDoubleAsterisks()
	{
		$text = "This is a sentence with **strong** text.";
		$dom = new \DOMElement('strong', 'strong');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function strongTextCanSpanMultipleWords()
	{
		$text = "This is a sentence with **strong text**.";
		$dom = new \DOMElement('strong', 'strong text');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function textCanContainMultipleStrongSections()
	{
		$text = "This is __a sentence__ with __strong text__.";
		$dom = new \DOMElement('strong', 'a sentence');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function aWordCannotContainStrongParts()
	{
		$text = "This is not a st**ro**ng word.";
		$this->assertDoesNotCreateDomFromText($text);
	}

	/**
	 * @test
	 */
	public function firstDoubleAsterisksCannotHaveSpaceBehindIt()
	{
		$text = "This is not a sentence with ** strong** text.";
		$this->assertDoesNotCreateDomFromText($text);
	}

	/**
	 * @test
	 */
	public function lastDoubleAsterisksCannotHaveSpaceBeforeIt()
	{
		$text = "This is not a sentence with **strong ** text.";
		$this->assertDoesNotCreateDomFromText($text);
	}

	/**
	 * @test
	 */
	public function firstAsteriskMustBePrecededBySpace()
	{
		$text = "This is not a sentence with**strong** text.";
		$this->assertDoesNotCreateDomFromText($text);
	}

	/**
	 * @test
	 */
	public function usesMostOutwardAsterisksOnConsequtive()
	{
		$text = "This is a sentence with ***strong text***.";
		$dom = new \DOMElement('strong', '*strong text*');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function knowsWhenEmphasisShouldBeFirst()
	{
		$text = "a ***test** test*.";
		$this->assertDoesNotCreateDomFromText($text);
	}

	/**
	 * @test
	 */
	public function canContainMultiplication()
	{
		$text = "The **result of 5*6 is thirtyfive**.";
		$dom = new \DOMElement('strong', 'result of 5*6 is thirtyfive');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function incorrectNesting()
	{
		$text = "**test  *test** test*";
		$dom = new \DOMElement('strong', 'test  *test');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 * 
	 * was failing PHPMarkdown test
	 */
	public function canBeSingleListItemContentContainingFullyItalicizedText()
	{
		$text = "___test test___";
		$dom = new \DOMElement('strong', '_test test_');
		$this->assertCreatesDomFromText($dom, $text);
	}
}