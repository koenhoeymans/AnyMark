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

	public function createSection($text)
	{
		$section = new \AnyMark\ElementTree\Element('section');
		$section->append(new \AnyMark\ElementTree\Text($text));

		return $section;
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
		$specialSection = $this->createSection('some text');

		$this->assertEquals($specialSection, $this->applyPattern($text));
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
		$specialSection = $this->createSection("\tsome text");

		$this->assertEquals($specialSection, $this->applyPattern($text));
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
		$specialSection = $this->createSection("some text\ncontinued on another line");

		$this->assertEquals($specialSection, $this->applyPattern($text));
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
		$specialSection = $this->createSection("some text\ncontinued on another line");

		$this->assertEquals($specialSection, $this->applyPattern($text));
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
		$specialSection = $this->createSection("a section\n\nsection continued\n\nsection:\n\tdeeper nested section\n\nsection continued");

		$this->assertEquals($specialSection, $this->applyPattern($text));
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
		$specialSection = $this->createSection('a section');
		$specialSection->setAttribute('class', 'class');

		$this->assertEquals($specialSection, $this->applyPattern($text));
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

		$specialSection = $this->createSection('a section');

		$this->assertEquals($specialSection, $this->applyPattern($text));
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
		$specialSection = $this->createSection("a section\n\n");
		$specialSection->setAttribute('class', 'class');

		$this->assertEquals($specialSection, $this->applyPattern($text));
	}
} 