<?php

namespace Anymark;

class AnyMark_Pattern_Patterns_EmphasisTest extends PatternReplacementAssertions
{
	public function setup()
	{
		$this->pattern = new \AnyMark\Pattern\Patterns\Emphasis();
	}

	protected function getPattern()
	{
		return $this->pattern;
	}

	public function createEm($text)
	{
		$em = $this->elementTree()->createElement('em');
		$text = $this->elementTree()->createText($text);
		$em->append($text);

		return $em;
	}

	/**
	 * @test
	 */
	public function emphasizedTextIsPlacedBetweenSingleAsterisks()
	{
		$text = "This is a sentence with *emphasized* text.";

		$this->assertEquals($this->createEm('emphasized'), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function emphasizedTextCanSpanMultipleWords()
	{
		$text = "This is a sentence with *emphasized text*.";

		$this->assertEquals($this->createEm('emphasized text'), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function textCanContainMultipleEmphasizedSections()
	{
		$text = "This is a *sentence* with *emphasized* text.";

		$this->assertEquals($this->createEm('sentence'), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function aWordCannotContainEmphasizedParts()
	{
		$text = "This is not a b*ol*d word.";

		$this->assertEquals($this->createEm('ol'), $this->applyPattern($text));
	}

// 	/**
// 	 * @test
// 	 */
// 	public function multiplicationsDoNotInfluenceEmphasizedText()
// 	{
// 		$text = "The result of 5*6, or 6 * 5 is 35, or *thirtyfive* in letters.";

// 		$this->assertEquals($this->createEm('thirtyfive'), $this->applyPattern($text));
// 	}

// 	/**
// 	 * @test
// 	 */
// 	public function canContainMultiplication()
// 	{
// 		$text = "The *result of 5*6 is thirtyfive*.";

// 		$this->assertEquals($this->createEm('result of 5*6 is thirtyfive'), $this->applyPattern($text));
// 	}

	/**
	 * @test
	 */
	public function firstAsteriskCannotHaveSpaceBehindIt()
	{
		$text = "This is not a sentence with * emphasized* text.";

		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function lastAsteriskCannotHaveSpaceBeforeIt()
	{
		$text = "This is not a sentence with *emphasized * text.";

		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canBeStartOfText()
	{
		$text = "*emphasized* text.";

		$this->assertEquals($this->createEm('emphasized'), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function leavesStrongTextAsStrongText()
	{
		$text = "foo **[**Link**](url)** bar";

		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canContainStrongText()
	{
		$text = "*emphasized with **strong*** text.";

		$this->assertEquals($this->createEm('emphasized with **strong**'), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canContainStrongText2()
	{
		$text = "***emphasized** with strong* text.";

		$this->assertEquals($this->createEm('**emphasized** with strong'), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canContainStrongText3()
	{
		$text = "___emphasized__ with strong_ text.";
	
		$this->assertEquals($this->createEm('__emphasized__ with strong'), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canContainStrongText4()
	{
		$text = "*also **emphasized** with strong* text.";

		$this->assertEquals($this->createEm('also **emphasized** with strong'), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function strongTextIsFirst()
	{
		$text = "foo ***xyz*** bar";

		$this->assertEquals(null, $this->applyPattern($text));
	}

// 	/**
// 	 * @test
// 	 */
// 	public function emphasisInside()
// 	{
// 		$text = "*test   *test*  test*";

// 		$this->assertEquals($this->createEm('test   *test'), $this->applyPattern($text));
// 	}

// 	/**
// 	 * @test
// 	 */
// 	public function incorrectNestingWithStrongFirst()
// 	{
// 		$text = "**x *y** z*";

// 		$this->assertEquals(null, $this->applyPattern($text));
// 	}

// 	/**
// 	 * @test
// 	 */
// 	public function incorrectNestingWithEmphasisFirst()
// 	{
// 		$text = "*test **test* test**";

// 		$this->assertEquals($this->createEm('test **test'), $this->applyPattern($text));
// 	}

	/**
	 * @test
	 */
	public function onlyAsterisksAreNotEmphasis()
	{
		$text = "This is not a sentence with ****.";
		$this->assertEquals(null, $this->applyPattern($text));

		$text = "This is not a sentence with *****.";
		$this->assertEquals(null, $this->applyPattern($text));

		$text = "This is not **** a sentence with.";
		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function insideStrongLeavesStrongFirst()
	{
		$text = "**strong *and* emphasis**";
		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function insideStrongLeavesStrongFirst2()
	{
		$text = "**foo *bar***";
		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function insideStrongLeavesStrongFirst3()
	{
		$text = "***foo* bar**";
		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function insideStrongLeavesStrongFirst4()
	{
		$text = "a ___test_ test__.";
		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function middleWordEmphasis()
	{
		$text = "a**b**";
		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function middleWordEmphasis2()
	{
		$text = "**a**b";
		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function noEmphasisWhenEscaped()
	{
		$text = "no \*a* emphasis";
		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function noEmphasisWhenEscaped2()
	{
		$text = "no *a\* emphasis";
		$this->assertEquals(null, $this->applyPattern($text));
	}
}