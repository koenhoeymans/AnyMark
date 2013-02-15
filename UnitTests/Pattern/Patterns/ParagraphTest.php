<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Pattern_Patterns_ParagraphTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->pattern = new \AnyMark\Pattern\Patterns\Paragraph();
	}

	public function getPattern()
	{
		return $this->pattern;
	}

	public function createP($text)
	{
		$p = new \AnyMark\ElementTree\Element('p');
		$text = new \AnyMark\ElementTree\Text($text);
		$p->append($text);

		return $p;
	}

	/**
	 * @test
	 */
	public function emptyLineThenTextThenEmptyLineIsParagraph()
	{
		$text = "\n\nparagraph\n\n";
		$p = $this->createP('paragraph');

		$this->assertEquals($p, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function emptyLineThenTextThenLineBreakAndEndOfTextIsParagraph()
	{
		$text = "\n\nparagraph\n";
		$p = $this->createP('paragraph');

		$this->assertEquals($p, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function emptyLineThenTextThenEndOfTextIsParagraph()
	{
		$text = "\n\nparagraph";
		$p = $this->createP('paragraph');

		$this->assertEquals($p, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canAlsoBeStartOfString()
	{
		$text = "paragraph\n\n";
		$p = $this->createP('paragraph');

		$this->assertEquals($p, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function cannotBeBothStartAndEndOfString()
	{
		$this->assertEquals(null, $this->applyPattern('paragraph'));
	}

	/**
	 * @test
	 */
	public function multipleParagraphsCanBePlacedAfterEachOther()
	{
		$text =
"paragraph

another

yet another";
		$p = $this->createP('paragraph');

		$this->assertEquals($p, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function aParagraphCannotContainOnlyWhiteSpace()
	{
		$this->assertEquals(null, $this->applyPattern("\n\n \n\n"));
	}

	/**
	 * @test
	 */
	public function indentationOfThreeSpacesMaximum()
	{
		$text = "\n\n paragraph\n\n";
		$p = $this->createP('paragraph');

		$this->assertEquals($p, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function indentedMoreThanThreeSpacesIsNoParagraph()
	{
		$text = "\n\n    paragraph\n\n";
		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function indentedATabIsNoParagraph()
	{
		$text = "\n\n\tparagraph\n\n";
		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function followingLinesCanBeIndentedTheSame()
	{
		$text =
"

 paragraph
 paragraph continued

";
		$p = $this->createP("paragraph\nparagraph continued");

		$this->assertEquals($p, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function followingLinesCanBeLeftUnindented()
	{
		$text =
"

 paragraph
paragraph continued

";
		$p = $this->createP("paragraph\nparagraph continued");

		$this->assertEquals($p, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function followingLinesMustNotBeMoreIndented()
	{
		$text =
"

paragraph
 paragraph not continued

";
		$p = $this->createP("paragraph");

		$this->assertEquals($p, $this->applyPattern($text));
	}
}