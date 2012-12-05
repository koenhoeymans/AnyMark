<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Pattern_Patterns_DefinitionListTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->dl = new \AnyMark\Pattern\Patterns\DefinitionList();
	}

	public function getPattern()
	{
		return $this->dl;
	}

	/**
	 * @test
	 */
	public function aDlConsistsOfTermFollowedOnNewLineByColonAndDescription()
	{
		$text =
'paragraph

a term
:	explanation

paragraph';

		$dom = new \DOMElement('dl', "a term\n:	explanation");
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function canBeStartOfInputString()
	{
				$text =
'term
:	explanation

paragraph';

		$dom = new \DOMElement('dl', "term\n:	explanation");
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function canBeEndOfInputString()
	{
		$text =
'paragraph

term
:	explanation';

		$dom = new \DOMElement('dl', "term\n:	explanation");
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function thereCanBeMultipleDescriptions()
	{
		$text =
'paragraph

term c
:	explanation x
:	explanation y

paragraph';

		$dom = new \DOMElement('dl', "term c\n:	explanation x\n:	explanation y");
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function thereCanBeMultipleTermsAndDescriptionsWithParagraphs()
	{
		$text =
'paragraph

term a
term b
term c
:	explanation x

	Continuation of explanation.

:	explanation y

	Continuation of explanation.

		code

paragraph';

		$listText =
'term a
term b
term c
:	explanation x

	Continuation of explanation.

:	explanation y

	Continuation of explanation.

		code';

		$dom = new \DOMElement('dl', $listText);
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function aDefinitionListCanContainMultipleTermsEachWithDescription()
	{
		$text =
'paragraph

term
:	term explanation

other term
:	other term explanation

paragraph';

		$listText =
'term
:	term explanation

other term
:	other term explanation';

		$dom = new \DOMElement('dl', $listText);
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function codeShouldNotBeMistakenForDefinition()
	{
		$text = "

This paragraph is followed by:

	: dd

";

		$this->assertDoesNotCreateDomFromText($text);
	}
}