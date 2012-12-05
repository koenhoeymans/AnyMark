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

	/**
	 * @test
	 */
	public function emptyLineThenTextThenEmptyLineIsParagraph()
	{
		$text = "\n\nparagraph\n\n";
		$dom = new \DOMElement('p', 'paragraph');
		$this->assertCreatesDomFromText($dom, $text);;
	}

	/**
	 * @test
	 */
	public function emptyLineThenTextThenLineBreakAndEndOfTextIsParagraph()
	{
		$text = "\n\nparagraph\n";
		$dom = new \DOMElement('p', 'paragraph');
		$this->assertCreatesDomFromText($dom, $text);;
	}

	/**
	 * @test
	 */
	public function emptyLineThenTextThenEndOfTextIsParagraph()
	{
		$text = "\n\nparagraph";
		$dom = new \DOMElement('p', 'paragraph');
		$this->assertCreatesDomFromText($dom, $text);;
	}

	/**
	 * @test
	 */
	public function canAlsoBeStartOfString()
	{
		$text = "paragraph\n\n";
		$dom = new \DOMElement('p', 'paragraph');
		$this->assertCreatesDomFromText($dom, $text);;
	}

	/**
	 * @test
	 */
	public function cannotBeBothStartAndEndOfString()
	{
		$text = "paragraph";
		$this->assertDoesNotCreateDomFromText($text);
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
		$dom = new \DOMElement('p', 'paragraph');
		$this->assertCreatesDomFromText($dom, $text);;
	}

	/**
	 * @test
	 */
	public function aParagraphCannotContainOnlyWhiteSpace()
	{
		$text = "\n\n  \n\n";
		$this->assertDoesNotCreateDomFromText($text);
	}

	/**
	 * @test
	 */
	public function indentationOfThreeSpacesMaximum()
	{
		$text = "\n\n paragraph\n\n";
		$dom = new \DOMElement('p', 'paragraph');
		$this->assertCreatesDomFromText($dom, $text);;
	}

	/**
	 * @test
	 */
	public function indentedMoreThanThreeSpacesIsNoParagraph()
	{
		$text = "\n\n    paragraph\n\n";
		$this->assertDoesNotCreateDomFromText($text);
	}

	/**
	 * @test
	 */
	public function indentedATabIsNoParagraph()
	{
		$text = "\n\n\tparagraph\n\n";
		$this->assertDoesNotCreateDomFromText($text);
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

		$dom = new \DOMElement('p', "paragraph\nparagraph continued");
		$this->assertCreatesDomFromText($dom, $text);;
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

		$dom = new \DOMElement('p', "paragraph\nparagraph continued");
		$this->assertCreatesDomFromText($dom, $text);;
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
	
		$dom = new \DOMElement('p', "paragraph");
		$this->assertCreatesDomFromText($dom, $text);;
	}
}