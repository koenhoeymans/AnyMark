<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Pattern_Patterns_HorizontalRuleTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->pattern = new \AnyMark\Pattern\Patterns\HorizontalRule();
	}

	protected function getPattern()
	{
		return $this->pattern;
	}

	public function createHr()
	{
		return $this->elementTree()->createElement('hr');
	}

	/**
	 * @test
	 */
	public function atLeastThreeHyphensOnARuleByThemselvesProduceAHorizontalRule()
	{
		$text = "\n---\n";

		$this->assertEquals($this->createHr(), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function atLeastThreeAsteriskOnARuleByThemselvesProduceAHorizontalRule()
	{
		$text = "\n***\n";

		$this->assertEquals($this->createHr(), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function atLeastThreeUnderscoresOnARuleByThemselvesProduceAHorizontalRule()
	{
		$text = "\n___\n";

		$this->assertEquals($this->createHr(), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function spacingIsAllowed()
	{
		$text = "\n * * *\n";

		$this->assertEquals($this->createHr(), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function moreCharactersAreAllowed()
	{
		$text = "\n------------\n";

		$this->assertEquals($this->createHr(), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function sameCharacterMustBeUsed()
	{
		$text = "\n-*-\n";
		
		$this->assertEquals(null, $this->applyPattern($text));
	}
}