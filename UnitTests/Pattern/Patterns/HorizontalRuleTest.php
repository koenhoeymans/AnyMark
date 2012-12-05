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

	/**
	 * @test
	 */
	public function atLeastThreeHyphensOnARuleByThemselvesProduceAHorizontalRule()
	{
		$text = "\n---\n";
		$dom = new \DOMElement('hr');

		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function atLeastThreeAsteriskOnARuleByThemselvesProduceAHorizontalRule()
	{
		$text = "\n***\n";
		$dom = new \DOMElement('hr');
		
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function atLeastThreeUnderscoresOnARuleByThemselvesProduceAHorizontalRule()
	{
		$text = "\n___\n";
		$dom = new \DOMElement('hr');
		
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function spacingIsAllowed()
	{
		$text = "\n * * *\n";
		$dom = new \DOMElement('hr');
		
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function moreCharactersAreAllowed()
	{
		$text = "\n------------\n";
		$dom = new \DOMElement('hr');
		
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function sameCharacterMustBeUsed()
	{
		$text = "\n-*-\n";
		
		$this->assertDoesNotCreateDomFromText($text);
	}
}