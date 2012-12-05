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

	/**
	 * @test
	 */
	public function emphasizedTextIsPlacedBetweenSingleAsterisks()
	{
		$text = "This is a sentence with *emphasized* text.";
		$dom = new \DOMElement('em', 'emphasized');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function emphasizedTextCanSpanMultipleWords()
	{
		$text = "This is a sentence with *emphasized text*.";
		$dom = new \DOMElement('em', 'emphasized text');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function textCanContainMultipleEmphasizedSections()
	{
		$text = "This is *a sentence* with *emphasized text*.";
		$dom = new \DOMElement('em', 'a sentence');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function aWordCannotContainEmphasizedParts()
	{
		$text = "This is not a b*ol*d word.";
		$this->assertDoesNotCreateDomFromText($text);
	}

	/**
	 * @test
	 */
	public function multiplicationsDoNotInfluenceEmphasizedText()
	{
		$text = "The result of 5*6, or 6 * 5 is 35, or *thirtyfive* in letters.";
		$dom = new \DOMElement('em', 'thirtyfive');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function canContainMultiplication()
	{
		$text = "The *result of 5*6 is thirtyfive*.";
		$dom = new \DOMElement('em', 'result of 5*6 is thirtyfive');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function firstAsteriskCannotHaveSpaceBehindIt()
	{
		$text = "This is not a sentence with * emphasized* text.";
		$this->assertDoesNotCreateDomFromText($text);
	}

	/**
	 * @test
	 */
	public function lastAsteriskCannotHaveSpaceBeforeIt()
	{
		$text = "This is not a sentence with *emphasized * text.";
		$this->assertDoesNotCreateDomFromText($text);
	}

	/**
	 * @test
	 */
	public function firstAsteriskMustBePrecededBySpace()
	{
		$text = "This is not a sentence with*emphasized* text.";
		$this->assertDoesNotCreateDomFromText($text);
	}

	/**
	 * @test
	 */
	public function canBeStartOfText()
	{
		$text = "*emphasized* text.";
		$dom = new \DOMElement('em', 'emphasized');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function canContainStrongText()
	{
		$text = "*emphasized with **strong*** text.";
		$dom = new \DOMElement('em', 'emphasized with **strong**');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function canContainStrongText2()
	{
		$text = "***emphasized** with strong* text.";
		$dom = new \DOMElement('em', '**emphasized** with strong');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function canContainStrongText3()
	{
		$text = "*also **emphasized** with strong* text.";
		$dom = new \DOMElement('em', 'also **emphasized** with strong');
		$this->assertCreatesDomFromText($dom, $text);
	}
}