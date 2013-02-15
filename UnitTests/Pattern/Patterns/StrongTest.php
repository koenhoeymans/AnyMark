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

	public function createStrong($text)
	{
		$strong = new \AnyMark\ElementTree\Element('strong');
		$strong->append(new \AnyMark\ElementTree\Text($text));

		return $strong;
	}

	/**
	 * @test
	 */
	public function strongTextIsPlacedBetweenDoubleAsterisks()
	{
		$text = "This is a sentence with **strong** text.";
		$strong = $this->createStrong('strong');

		$this->assertEquals($strong, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function strongTextCanSpanMultipleWords()
	{
		$text = "This is a sentence with **strong text**.";
		$strong = $this->createStrong('strong text');

		$this->assertEquals($strong, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function textCanContainMultipleStrongSections()
	{
		$text = "This is __a sentence__ with __strong text__.";
		$strong = $this->createStrong('a sentence');

		$this->assertEquals($strong, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function aWordCannotContainStrongParts()
	{
		$text = "This is not a st**ro**ng word.";
		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function firstDoubleAsterisksCannotHaveSpaceBehindIt()
	{
		$text = "This is not a sentence with ** strong** text.";
		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function lastDoubleAsterisksCannotHaveSpaceBeforeIt()
	{
		$text = "This is not a sentence with **strong ** text.";
		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function firstAsteriskMustBePrecededBySpace()
	{
		$text = "This is not a sentence with**strong** text.";
		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function usesMostOutwardAsterisksOnConsequtive()
	{
		$text = "This is a sentence with ***strong text***.";
		$strong = $this->createStrong('*strong text*');

		$this->assertEquals($strong, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function knowsWhenEmphasisShouldBeFirst()
	{
		$text = "a ***test** test*.";
		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canContainMultiplication()
	{
		$text = "The **result of 5*6 is thirtyfive**.";
		$strong = $this->createStrong('result of 5*6 is thirtyfive');

		$this->assertEquals($strong, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function incorrectNesting()
	{
		$text = "**test  *test** test*";
		$strong = $this->createStrong('test  *test');

		$this->assertEquals($strong, $this->applyPattern($text));
	}

	/**
	 * @test
	 * 
	 * was failing PHPMarkdown test
	 */
	public function canBeSingleListItemContentContainingFullyItalicizedText()
	{
		$text = "___test test___";
		$strong = $this->createStrong('_test test_');

		$this->assertEquals($strong, $this->applyPattern($text));
	}
}