<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Pattern_Patterns_EmphasisTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
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
		$em = new \AnyMark\ElementTree\Element('em');
		$text = new \AnyMark\ElementTree\Text($text);
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
		$text = "This is *a sentence* with *emphasized text*.";

		$this->assertEquals($this->createEm('a sentence'), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function aWordCannotContainEmphasizedParts()
	{
		$text = "This is not a b*ol*d word.";

		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function multiplicationsDoNotInfluenceEmphasizedText()
	{
		$text = "The result of 5*6, or 6 * 5 is 35, or *thirtyfive* in letters.";

		$this->assertEquals($this->createEm('thirtyfive'), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canContainMultiplication()
	{
		$text = "The *result of 5*6 is thirtyfive*.";

		$this->assertEquals($this->createEm('result of 5*6 is thirtyfive'), $this->applyPattern($text));
	}

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
	public function firstAsteriskMustBePrecededBySpace()
	{
		$text = "This is not a sentence with*emphasized* text.";

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
		$text = "*also **emphasized** with strong* text.";

		$this->assertEquals($this->createEm('also **emphasized** with strong'), $this->applyPattern($text));
	}
}