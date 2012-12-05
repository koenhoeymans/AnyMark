<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

/**
 * @todo Tests would be more readable with:
 * 
 * 	$this->element('h1', 'header')->withAttr('id', 'header');
 *
 */
class AnyMark_Pattern_Patterns_HeaderTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->pattern = new \AnyMark\Pattern\Patterns\Header();
	}

	protected function getPattern()
	{
		return $this->pattern;
	}

	// ------------ Setext style ------------

	/**
	 * @test
	 */
	public function headerIsFollowedByLineOfAtLeastThreeCharacters()
	{
		$text = "\n\nheader\n---\n\n";
		$doc = new \DOMDocument();
		$el = $doc->createElement('h1', 'header');
		$doc->appendChild($el);
		$el->setAttribute('id', 'header');
		$this->assertCreatesDomFromText($el, $text);
	}

	/**
	 * @test
	 */
	public function theLineOfAtLeastThreeCharactersMayNotBePrecededByABlankLine()
	{
		$text = "\n\nno header\n\n---\n\n";
		$this->assertDoesNotCreateDomFromText($text);
	}

	/**
	 * @test
	 */
	public function headerIsOptionallyPrecededByLineOfCharacters()
	{
		$text = "\n\n---\na header\n---\n\n";
		$doc = new \DOMDocument();
		$el = $doc->createElement('h1', 'a header');
		$doc->appendChild($el);
		$el->setAttribute('id', 'a-header');
		$this->assertCreatesDomFromText($el, $text);
	}

	/**
	 * @test
	 */
	public function characterLinesCanBeMoreThanThreeCharacters()
	{
		$text = "\n\n-----\na header\n-----\n\n";
		$doc = new \DOMDocument();
		$el = $doc->createElement('h1', 'a header');
		$doc->appendChild($el);
		$el->setAttribute('id', 'a-header');
		$this->assertCreatesDomFromText($el, $text);
	}

	/**
	 * @test
	 */
	public function onlyTheFirstThreeCharactersCount()
	{
		$text = "\n\na header\n---###\n\n";
		$doc = new \DOMDocument();
		$el = $doc->createElement('h1', 'a header');
		$doc->appendChild($el);
		$el->setAttribute('id', 'a-header');
		$this->assertCreatesDomFromText($el, $text);
	}

	/**
	 * @test
	 */
	public function characterLinesCannotBeLessThanThreeCharacters()
	{
		$text = "\n\n--\nthis is no header\n--\n\n";
		$this->assertDoesNotCreateDomFromText($text);
	}

	/**
	 * @test
	 */
	public function lineCharactersMayContainDashSigns()
	{
		$text = "\n\n---\na header\n---\n\n";
		$doc = new \DOMDocument();
		$el = $doc->createElement('h1', 'a header');
		$doc->appendChild($el);
		$el->setAttribute('id', 'a-header');
		$this->assertCreatesDomFromText($el, $text);
	}

	/**
	 * @test
	 */
	public function lineCharactersMayContainEqualSigns()
	{
		$text = "\n\n===\na header\n===\n\n";
		$doc = new \DOMDocument();
		$el = $doc->createElement('h1', 'a header');
		$doc->appendChild($el);
		$el->setAttribute('id', 'a-header');
		$this->assertCreatesDomFromText($el, $text);
	}

	/**
	 * @test
	 */
	public function lineCharactersMayContainPlusSigns()
	{
		$text = "\n\n+++\na header\n+++\n\n";
		$doc = new \DOMDocument();
		$el = $doc->createElement('h1', 'a header');
		$doc->appendChild($el);
		$el->setAttribute('id', 'a-header');
		$this->assertCreatesDomFromText($el, $text);
	}

	/**
	 * @test
	 */
	public function lineCharactersMayContainStarSigns()
	{
		$text = "\n\n***\na header\n***\n\n";
		$doc = new \DOMDocument();
		$el = $doc->createElement('h1', 'a header');
		$doc->appendChild($el);
		$el->setAttribute('id', 'a-header');
		$this->assertCreatesDomFromText($el, $text);
	}

	/**
	 * @test
	 */
	public function lineCharactersMayContainCaretSigns()
	{
		$text = "\n\n^^^\na header\n^^^\n\n";
		$doc = new \DOMDocument();
		$el = $doc->createElement('h1', 'a header');
		$doc->appendChild($el);
		$el->setAttribute('id', 'a-header');
		$this->assertCreatesDomFromText($el, $text);
	}

	/**
	 * @test
	 */
	public function lineCharactersMayContainNumberSignSigns()
	{
		$text = "\n\n###\na header\n###\n\n";
		$doc = new \DOMDocument();
		$el = $doc->createElement('h1', 'a header');
		$doc->appendChild($el);
		$el->setAttribute('id', 'a-header');
		$this->assertCreatesDomFromText($el, $text);
	}

	/**
	 * @test
	 */
	public function lineOfStartingAndEndingCharactersMustNotBeSame()
	{
		$text = "\n\n=-=\na header\n=-=\n\n";
		$doc = new \DOMDocument();
		$el = $doc->createElement('h1', 'a header');
		$doc->appendChild($el);
		$el->setAttribute('id', 'a-header');
		$this->assertCreatesDomFromText($el, $text);
	}

	/**
	 * @test
	 */
	public function levelOfHeadersIsAssignedByOrderOfAppearance()
	{
		$text = "\n\nfirst\n---\n\nsecond\n===\n\nthird\n+++\n\nfourth\n***\n\nfifth\n^^^\n\nsixth\n###\n\n";
		$doc = new \DOMDocument();
		$el = $doc->createElement('h1', 'first');
		$doc->appendChild($el);
		$el->setAttribute('id', 'first');
		$this->assertCreatesDomFromText($el, $text);

		$text = "\n\nsecond\n===\n\nthird\n+++\n\nfourth\n***\n\nfifth\n^^^\n\nsixth\n###\n\n";
		$doc = new \DOMDocument();
		$el = $doc->createElement('h2', 'second');
		$doc->appendChild($el);
		$el->setAttribute('id', 'second');
		$this->assertCreatesDomFromText($el, $text);

		$text = "\n\nthird\n+++\n\nfourth\n***\n\nfifth\n^^^\n\nsixth\n###\n\n";
		$doc = new \DOMDocument();
		$el = $doc->createElement('h3', 'third');
		$doc->appendChild($el);
		$el->setAttribute('id', 'third');
		$this->assertCreatesDomFromText($el, $text);

		$text = "\n\nfourth\n***\n\nfifth\n^^^\n\nsixth\n###\n\n";
		$doc = new \DOMDocument();
		$el = $doc->createElement('h4', 'fourth');
		$doc->appendChild($el);
		$el->setAttribute('id', 'fourth');
		$this->assertCreatesDomFromText($el, $text);

		$text = "\n\nfifth\n^^^\n\nsixth\n###\n\n";
		$doc = new \DOMDocument();
		$el = $doc->createElement('h5', 'fifth');
		$doc->appendChild($el);
		$el->setAttribute('id', 'fifth');
		$this->assertCreatesDomFromText($el, $text);

		$text = "\n\nsixth\n###\n\n";
		$doc = new \DOMDocument();
		$el = $doc->createElement('h6', 'sixth');
		$doc->appendChild($el);
		$el->setAttribute('id', 'sixth');
		$this->assertCreatesDomFromText($el, $text);
	}

	/**
	 * @test
	 */
	public function levelOfHeadersIsRemembered()
	{
		$text = "\n\nfirst\n---\n\nsecond\n===\n\nthird\n+++\n\nfourth\n***\n\nfifth\n^^^\n\nsixth\n###\n\n";
		$doc = new \DOMDocument();
		$el = $doc->createElement('h1', 'first');
		$doc->appendChild($el);
		$el->setAttribute('id', 'first');
		$this->assertCreatesDomFromText($el, $text);

		$text = "\n\nsecond\n===\n\nthird\n+++\n\nfourth\n***\n\nfifth\n^^^\n\nsixth\n###\n\n";
		$doc = new \DOMDocument();
		$el = $doc->createElement('h2', 'second');
		$doc->appendChild($el);
		$el->setAttribute('id', 'second');
		$this->assertCreatesDomFromText($el, $text);

		$text = "para\n\nother second\n===\n\npara";
		$doc = new \DOMDocument();
		$el = $doc->createElement('h2', 'other second');
		$doc->appendChild($el);
		$el->setAttribute('id', 'other-second');
		$this->assertCreatesDomFromText($el, $text);
	}

	/**
	 * @test
	 */
	public function headerCanBeStartOfDocument()
	{
		$text = "header\n---\n\n";
		$doc = new \DOMDocument();
		$el = $doc->createElement('h1', 'header');
		$doc->appendChild($el);
		$el->setAttribute('id', 'header');
		$this->assertCreatesDomFromText($el, $text);
	}

	/**
	 * @test
	 */
	public function headerCanFollowStartPlusNewline()
	{
		$text = "\nheader\n---\n\n";
		$doc = new \DOMDocument();
		$el = $doc->createElement('h1', 'header');
		$doc->appendChild($el);
		$el->setAttribute('id', 'header');
		$this->assertCreatesDomFromText($el, $text);
	}

	/**
	 * @test
	 */
	public function headerMustNotFollowABlankLine()
	{
		$text = "para\nheader\n---\n\n";
		$doc = new \DOMDocument();
		$el = $doc->createElement('h1', 'header');
		$doc->appendChild($el);
		$el->setAttribute('id', 'header');
		$this->assertCreatesDomFromText($el, $text);
	}

	/**
	 * @test
	 */
	public function headerMustNotBeFollowedByBlankLine()
	{
		$text = "\n\nheader\n---\nparagraph\n\n";
		$doc = new \DOMDocument();
		$el = $doc->createElement('h1', 'header');
		$doc->appendChild($el);
		$el->setAttribute('id', 'header');
		$this->assertCreatesDomFromText($el, $text);
	}

	/**
	 * @test
	 */
	public function canBeIndentedByUptoThreeSpaces()
	{
		$text = "\n\n   header preceded by 3 spaces\n---\n\n";
		$doc = new \DOMDocument();
		$el = $doc->createElement('h1', 'header preceded by 3 spaces');
		$doc->appendChild($el);
		$el->setAttribute('id', 'header-preceded-by-3-spaces');
		$this->assertCreatesDomFromText($el, $text);

		$text = "\n\n    header preceded by 4 spaces\n---\n\n";
		$this->assertDoesNotCreateDomFromText($text);
	}

	//	------------ atx style ------------

	/**
	 * @test
	 */
	public function oneToSixHashesBeforeHeaderDeterminesHeaderLevel()
	{
		$text = "paragraph\n\n# level 1\n\nparagraph";
		$doc = new \DOMDocument();
		$el = $doc->createElement('h1', 'level 1');
		$doc->appendChild($el);
		$el->setAttribute('id', 'level-1');
		$this->assertCreatesDomFromText($el, $text);

		$text = "paragraph\n\n## level 2\n\nparagraph";
		$doc = new \DOMDocument();
		$el = $doc->createElement('h2', 'level 2');
		$doc->appendChild($el);
		$el->setAttribute('id', 'level-2');
		$this->assertCreatesDomFromText($el, $text);

		$text = "paragraph\n\n###### level 6\n\nparagraph";
		$doc = new \DOMDocument();
		$el = $doc->createElement('h6', 'level 6');
		$doc->appendChild($el);
		$el->setAttribute('id', 'level-6');
		$this->assertCreatesDomFromText($el, $text);
	}

	/**
	 * @test
	 */
	public function closingHashesAreOptional()
	{
		$text = "paragraph\n\n## level 2 #####\n\nparagraph";
		$doc = new \DOMDocument();
		$el = $doc->createElement('h2', 'level 2');
		$doc->appendChild($el);
		$el->setAttribute('id', 'level-2');
		$this->assertCreatesDomFromText($el, $text);
	}

	/**
	 * @test
	 */
	public function headerMustNotBeFollowedByBlankLine_2()
	{
		$text = "\n\n# header\nparagraph\n\n";
		$doc = new \DOMDocument();
		$el = $doc->createElement('h1', 'header');
		$doc->appendChild($el);
		$el->setAttribute('id', 'header');
		$this->assertCreatesDomFromText($doc, $text);
	}

	/**
	 * @test
	 * 
	 * Note difference with Setext style
	 */
	public function headerMustBePrecededByBlankLine()
	{
		$text = "paragraph\n# header\n\n";
		$this->assertDoesNotCreateDomFromText($text);
	}

	//	------------ id ------------

	public function assertCreatesId($expectedId, $fromHeaderText)
	{
		$text = "\n\n# $fromHeaderText\nparagraph\n\n";
		$doc = new \DOMDocument();
		$el = $doc->createElement('h1', $fromHeaderText);
		$doc->appendChild($el);
		$el->setAttribute('id', $expectedId);
		$this->assertCreatesDomFromText($doc, $text);
	}

	/**
	 * @test
	 */
	public function removesCharactersOtherThanAlfaNumUnderscoreHyphenPeriods()
	{
		$this->assertCreatesId('a2_-.z', 'a*2_-[.z');
	}

	/**
	 * @test
	 */
	public function removesEverythingUpToTheFirstLetter()
	{
		$this->assertCreatesId('words', '2. words');
	}

	/**
	 * @test
	 */
	public function appendsNumbersToDistinguishIds()
	{
		$doc = new \DOMDocument();
		$text1 = "\n\n# header\nparagraph\n\n";
		$text2 = "\n\n# header\nparagraph\n\n";

		$el1 = $doc->createElement('h1', 'header');
		$doc->appendChild($el1);
		$el1->setAttribute('id', 'header');
		$this->assertCreatesDomFromText($doc, $text1);

		$el2 = $doc->createElement('h1', 'header');
		$doc->replaceChild($el2, $el1);
		$el2->setAttribute('id', 'header-2');
		$this->assertCreatesDomFromText($doc, $text2);
	}
}