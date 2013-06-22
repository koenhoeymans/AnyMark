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
		$strong = $this->elementTree()->createElement('strong');
		$strong->append($this->elementTree()->createText($text));

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
	public function aWordCanContainStrongParts()
	{
		$text = "This is a st**ro**ng word.";
		$strong = $this->createStrong('ro');

		$this->assertEquals($strong, $this->applyPattern($text));
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

// 	/**
// 	 * @test
// 	 */
// 	public function canContainMultiplication()
// 	{
// 		$text = "The **result of 5*6 is thirtyfive**.";
// 		$strong = $this->createStrong('result of 5*6 is thirtyfive');

// 		$this->assertEquals($strong, $this->applyPattern($text));
// 	}

// 	/**
// 	 * @test
// 	 */
// 	public function incorrectNesting()
// 	{
// 		$text = "**test  *test** test*";
// 		$strong = $this->createStrong('test  *test');

// 		$this->assertEquals($strong, $this->applyPattern($text));
// 	}

// 	/**
// 	 * @test
// 	 */
// 	public function incorrectNestingWithEmphasisSecond()
// 	{
// 		$text = "**foo  *bar** baz*";
// 		$strong = $this->createStrong('foo  *bar');

// 		$this->assertEquals($strong, $this->applyPattern($text));
// 	}

	/**
	 * @test
	 */
	public function onlyUnderscoresIsNotStrong()
	{
		$text = "a ____ test.";
		$this->assertEquals(null, $this->applyPattern($text));

		$text = "a _______ test.";
		$this->assertEquals(null, $this->applyPattern($text));

		$text = "a ____foo test.";
		$this->assertEquals(null, $this->applyPattern($text));

		$text = "a _____foo test.";
		$this->assertEquals(null, $this->applyPattern($text));

		$text = "a foo_____ test.";
		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canContainStrong()
	{
		$text = "**foo  **bar** baz**";
		$strong = $this->createStrong('foo  **bar** baz');

		$this->assertEquals($strong, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canContainItalic()
	{
		$text = "__foo  _bar___";
		$strong = $this->createStrong('foo  _bar_');

		$this->assertEquals($strong, $this->applyPattern($text));		
	}

	/**
	 * @test
	 */
	public function canContainItalic2()
	{
		$text = "___foo_ bar__";
		$strong = $this->createStrong('_foo_ bar');

		$this->assertEquals($strong, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canContainEmphasis()
	{
		$text = "**foo *bar***";
		$strong = $this->createStrong('foo *bar*');

		$this->assertEquals($strong, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canContainEmphasis2()
	{
		$text = "***foo* bar**";
		$strong = $this->createStrong('*foo* bar');
	
		$this->assertEquals($strong, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function nesting()
	{
		$text = " **[**Link**](url)** ";
		$strong = $this->createStrong('[**Link**](url)');

		$this->assertEquals($strong, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function notStrongWhenEscaped()
	{
		$text = "not \**a** strong";	
		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function notStrongWhenEscaped2()
	{
		$text = "not **a\** strong";
		$this->assertEquals(null, $this->applyPattern($text));
	}
}