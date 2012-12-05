<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Pattern_Patterns_sectionTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->pattern = new \AnyMark\Pattern\Patterns\SpecialSection('section:', 'section');
	}

	protected function getPattern()
	{
		return $this->pattern;
	}

	/**
	 * @test
	 */
	public function sectionsAreIntroducedByBlankLineWordAndColonWithTextIndentedOnFollowingLine()
	{
		$text =
"A paragraph.

section:
	some text

Another paragraph.";

		$dom = new \DOMElement('section', 'some text');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function contentsIsUnindentedForLengthOfIndentationOfsectionWord()
	{
		$text =
"A paragraph.


section:
		some text

Another paragraph.";
	
		$dom = new \DOMElement('section', "\tsome text");
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function textCanSpanMultipleLines()
	{
		$text =
"A paragraph.

section:
	some text
	continued on another line

Another paragraph.";

		$dom = new \DOMElement('section', "some text\ncontinued on another line");
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function textCanSpanMultipleLinesLazyStyle()
	{
		$text =
"A paragraph.

section:
	some text
continued on another line

Another paragraph.";

		$dom = new \DOMElement('section', "some text\ncontinued on another line");
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function asectionCanContinueIndentedAfterNestedsection()
	{
		$text =
"A paragraph.

section:
	a section

	section continued

	section:
		deeper nested section

	section continued

another paragraph";

		$dom = new \DOMElement('section', "a section\n\nsection continued\n\nsection:\n\tdeeper nested section\n\nsection continued");
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function aClassNameCanBeSpecifified()
	{
		$this->pattern = new \AnyMark\Pattern\Patterns\SpecialSection('section:', 'section', 'class');
		
		$text =
"A paragraph.

section:
	a section

another paragraph";

		$domDoc = new \DOMDocument();
		$domEl = $domDoc->appendChild($domDoc->createElement('section', 'a section'));
		$domEl->setAttribute('class', 'class');
		$this->assertCreatesDomFromText($domEl, $text);
	}

	/**
	 * @test
	 */
	public function canBeEndOfText()
	{
		$this->pattern = new \AnyMark\Pattern\Patterns\SpecialSection('section:', 'section');
	
		$text =
"A paragraph.

section:
	a section";
	
		$dom = new \DOMElement('section', 'a section');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function aBlankLineCanBeInsertedBeforeStartOfText()
	{
		$this->pattern = new \AnyMark\Pattern\Patterns\SpecialSection('section:', 'section', 'class');
		
		$text =
"A paragraph.

section:

	a section

another paragraph";
		
		$domDoc = new \DOMDocument();
		$domEl = $domDoc->appendChild($domDoc->createElement('section', "a section\n\n"));
		$domEl->setAttribute('class', 'class');
		$this->assertCreatesDomFromText($domEl, $text);
	}
} 