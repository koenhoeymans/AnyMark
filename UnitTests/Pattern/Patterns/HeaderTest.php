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

	public function createHeader($level, $text)
	{
		$header = new \AnyMark\ElementTree\Element($level);
		$text = new \AnyMark\ElementTree\Text($text);
		$header->append($text);

		return $header;
	}

	// ------------ Setext style ------------

	/**
	 * @test
	 */
	public function headerIsFollowedByLineOfAtLeastThreeCharacters()
	{
		$text = "\n\nheader\n---\n\n";
		$header = $this->createHeader('h1', 'header');
		$header->setAttribute('id', 'header');

		$this->assertEquals($header, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function theLineOfAtLeastThreeCharactersMayNotBePrecededByABlankLine()
	{
		$text = "\n\nno header\n\n---\n\n";
		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function headerIsOptionallyPrecededByLineOfCharacters()
	{
		$text = "\n\n---\na header\n---\n\n";
		$header = $this->createHeader('h1', 'a header');
		$header->setAttribute('id', 'a-header');

		$this->assertEquals($header, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function characterLinesCanBeMoreThanThreeCharacters()
	{
		$text = "\n\n-----\na header\n-----\n\n";
		$header = $this->createHeader('h1', 'a header');
		$header->setAttribute('id', 'a-header');

		$this->assertEquals($header, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function onlyTheFirstThreeCharactersCount()
	{
		$text = "\n\na header\n---###\n\n";
		$header = $this->createHeader('h1', 'a header');
		$header->setAttribute('id', 'a-header');

		$this->assertEquals($header, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function characterLinesCannotBeLessThanThreeCharacters()
	{
		$text = "\n\n--\nthis is no header\n--\n\n";
		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function lineCharactersMayContainDashSigns()
	{
		$text = "\n\n---\na header\n---\n\n";
		$header = $this->createHeader('h1', 'a header');
		$header->setAttribute('id', 'a-header');

		$this->assertEquals($header, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function lineCharactersMayContainEqualSigns()
	{
		$text = "\n\n===\na header\n===\n\n";
		$header = $this->createHeader('h1', 'a header');
		$header->setAttribute('id', 'a-header');

		$this->assertEquals($header, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function lineCharactersMayContainPlusSigns()
	{
		$text = "\n\n+++\na header\n+++\n\n";
		$header = $this->createHeader('h1', 'a header');
		$header->setAttribute('id', 'a-header');

		$this->assertEquals($header, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function lineCharactersMayContainStarSigns()
	{
		$text = "\n\n***\na header\n***\n\n";
		$header = $this->createHeader('h1', 'a header');
		$header->setAttribute('id', 'a-header');

		$this->assertEquals($header, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function lineCharactersMayContainCaretSigns()
	{
		$text = "\n\n^^^\na header\n^^^\n\n";
		$header = $this->createHeader('h1', 'a header');
		$header->setAttribute('id', 'a-header');

		$this->assertEquals($header, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function lineCharactersMayContainNumberSignSigns()
	{
		$text = "\n\n###\na header\n###\n\n";
		$header = $this->createHeader('h1', 'a header');
		$header->setAttribute('id', 'a-header');

		$this->assertEquals($header, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function lineOfStartingAndEndingCharactersMustNotBeSame()
	{
		$text = "\n\n=-=\na header\n=-=\n\n";
		$header = $this->createHeader('h1', 'a header');
		$header->setAttribute('id', 'a-header');

		$this->assertEquals($header, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function levelOfHeadersIsAssignedByOrderOfAppearance()
	{
		$text = "\n\nfirst\n---\n\nsecond\n===\n\nthird\n+++\n\nfourth\n***\n\nfifth\n^^^\n\nsixth\n###\n\n";
		$header = $this->createHeader('h1', 'first');
		$header->setAttribute('id', 'first');

		$this->assertEquals($header, $this->applyPattern($text));

		$text = "\n\nsecond\n===\n\nthird\n+++\n\nfourth\n***\n\nfifth\n^^^\n\nsixth\n###\n\n";
		$header = $this->createHeader('h2', 'second');
		$header->setAttribute('id', 'second');

		$this->assertEquals($header, $this->applyPattern($text));

		$text = "\n\nthird\n+++\n\nfourth\n***\n\nfifth\n^^^\n\nsixth\n###\n\n";
		$header = $this->createHeader('h3', 'third');
		$header->setAttribute('id', 'third');

		$this->assertEquals($header, $this->applyPattern($text));

		$text = "\n\nfourth\n***\n\nfifth\n^^^\n\nsixth\n###\n\n";
		$header = $this->createHeader('h4', 'fourth');
		$header->setAttribute('id', 'fourth');

		$this->assertEquals($header, $this->applyPattern($text));

		$text = "\n\nfifth\n^^^\n\nsixth\n###\n\n";
		$header = $this->createHeader('h5', 'fifth');
		$header->setAttribute('id', 'fifth');

		$this->assertEquals($header, $this->applyPattern($text));

		$text = "\n\nsixth\n###\n\n";
		$header = $this->createHeader('h6', 'sixth');
		$header->setAttribute('id', 'sixth');

		$this->assertEquals($header, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function levelOfHeadersIsRemembered()
	{
		$text = "\n\nfirst\n---\n\nsecond\n===\n\nthird\n+++\n\nfourth\n***\n\nfifth\n^^^\n\nsixth\n###\n\n";
		$header = $this->createHeader('h1', 'first');
		$header->setAttribute('id', 'first');

		$this->assertEquals($header, $this->applyPattern($text));

		$text = "\n\nsecond\n===\n\nthird\n+++\n\nfourth\n***\n\nfifth\n^^^\n\nsixth\n###\n\n";
		$header = $this->createHeader('h2', 'second');
		$header->setAttribute('id', 'second');

		$this->assertEquals($header, $this->applyPattern($text));

		$text = "para\n\nother second\n===\n\npara";
		$header = $this->createHeader('h2', 'other second');
		$header->setAttribute('id', 'other-second');

		$this->assertEquals($header, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function headerCanBeStartOfDocument()
	{
		$text = "header\n---\n\n";
		$header = $this->createHeader('h1', 'header');
		$header->setAttribute('id', 'header');

		$this->assertEquals($header, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function headerCanFollowStartPlusNewline()
	{
		$text = "\nheader\n---\n\n";
		$header = $this->createHeader('h1', 'header');
		$header->setAttribute('id', 'header');

		$this->assertEquals($header, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function headerMustNotFollowABlankLine()
	{
		$text = "para\nheader\n---\n\n";
		$header = $this->createHeader('h1', 'header');
		$header->setAttribute('id', 'header');

		$this->assertEquals($header, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function headerMustNotBeFollowedByBlankLine()
	{
		$text = "\n\nheader\n---\nparagraph\n\n";
		$header = $this->createHeader('h1', 'header');
		$header->setAttribute('id', 'header');

		$this->assertEquals($header, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canBeIndentedByUptoThreeSpaces()
	{
		$text = "\n\n   header preceded by 3 spaces\n---\n\n";
		$header = $this->createHeader('h1', 'header preceded by 3 spaces');
		$header->setAttribute('id', 'header-preceded-by-3-spaces');

		$this->assertEquals($header, $this->applyPattern($text));

		$text = "\n\n    header preceded by 4 spaces\n---\n\n";
		$this->assertEquals(null, $this->applyPattern($text));
	}

 	//	------------ atx style ------------

	/**
	 * @test
	 */
	public function oneToSixHashesBeforeHeaderDeterminesHeaderLevel()
	{
		$text = "paragraph\n\n# level 1\n\nparagraph";
		$header = $this->createHeader('h1', 'level 1');
		$header->setAttribute('id', 'level-1');

		$this->assertEquals($header, $this->applyPattern($text));

		$text = "paragraph\n\n## level 2\n\nparagraph";
		$header = $this->createHeader('h2', 'level 2');
		$header->setAttribute('id', 'level-2');

		$this->assertEquals($header, $this->applyPattern($text));

		$text = "paragraph\n\n###### level 6\n\nparagraph";
		$header = $this->createHeader('h6', 'level 6');
		$header->setAttribute('id', 'level-6');

		$this->assertEquals($header, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function closingHashesAreOptional()
	{
		$text = "paragraph\n\n## level 2 #####\n\nparagraph";
		$header = $this->createHeader('h2', 'level 2');
		$header->setAttribute('id', 'level-2');

		$this->assertEquals($header, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function headerMustNotBeFollowedByBlankLine_2()
	{
		$text = "\n\n# header\nparagraph\n\n";
		$header = $this->createHeader('h1', 'header');
		$header->setAttribute('id', 'header');

		$this->assertEquals($header, $this->applyPattern($text));
	}

	/**
	 * @test
	 * 
	 * Note difference with Setext style
	 */
	public function headerMustBePrecededByBlankLine()
	{
		$text = "paragraph\n# header\n\n";
		$this->assertEquals(null, $this->applyPattern($text));
	}

 	//	------------ id ------------

	public function assertCreatesId($expectedId, $fromHeaderText)
	{
		$text = "\n\n# $fromHeaderText\nparagraph\n\n";
		$header = $this->createHeader('h1', $fromHeaderText);
		$header->setAttribute('id', $expectedId);

		$this->assertEquals($header, $this->applyPattern($text));
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
		$text1 = "\n\n# header\nparagraph\n\n";
		$text2 = "\n\n# header\nparagraph\n\n";

		$header = $this->createHeader('h1', 'header');
		$header->setAttribute('id', 'header');
		
		$this->assertEquals($header, $this->applyPattern($text1));

		$header = $this->createHeader('h1', 'header');
		$header->setAttribute('id', 'header-2');

		$this->assertEquals($header, $this->applyPattern($text2));
	}
}