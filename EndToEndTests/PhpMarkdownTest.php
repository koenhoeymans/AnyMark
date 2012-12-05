<?php

require_once('TestHelper.php');

/**
 * These are the PHPMarkdown tests as found in the test suite of PHPMarkdown.
 * 
 * Removed all newlines before ending code tag:
 * 
 * 	code
 * 	</code>
 * 
 * becomes
 * 
 * 	code</code>
 * 
 * Changed headers to headers with id's as expected outcome.
 */
class AnyMark_EndToEndTests_PhpMarkdownTest extends \AnyMark\EndToEndTests\Support\Tidy
{
	public function createTestFor($name)
	{
		$anyMark = \AnyMark\AnyMark::setup();
		$anyMark->changeSetup()
			->given('AnyMark\\Util\\InternalUrlBuilder')
			->thenUse('AnyMark\\Util\\ExtensionlessUrlBuilder');

		$parsedText = $anyMark->parse(file_get_contents(
			__DIR__
			. DIRECTORY_SEPARATOR . 'PhpMarkdown.mdtest'
			. DIRECTORY_SEPARATOR . $name . '.text'
		));

		$this->assertEquals(
			$this->tidy(file_get_contents(
				__DIR__
				. DIRECTORY_SEPARATOR . 'PhpMarkdown.mdtest'
				. DIRECTORY_SEPARATOR . $name . '.html'
			)),
			$this->tidy($parsedText)
		);
	}

	/**
	 * @test
	 * 
	 * Changed expected outcome for the email to an encoded one (cfr text emailautolinks)
	 */
	public function autoLinks()
	{
		$this->createTestFor('AutoLinks');
	}

	/**
	 * @test
	 */
	public function backslashEscapes()
	{
		$this->createTestFor('BackslashEscapes');
	}

	/**
	 * @test
	 */
	public function codeBlockInAListItem()
	{
		$this->createTestFor('CodeBlockInAListItem');
	}

	/**
	 * @test
	 */
	public function codeBlockOnSecondLine()
	{
		$this->createTestFor('CodeBlockOnSecondLine');
	}

	/**
	 * @test
	 */
	public function codeSpans()
	{
		$this->createTestFor('CodeSpans');
	}

	/**
	 * @test
	 */
	public function emailAutoLinks()
	{
		$this->createTestFor('EmailAutoLinks');
	}

	/**
	 * @test
	 * 
	 * Changed expected outcome for emphasis within words:
	 * Eg my_precious_text won't have emphasis. Neither will
	 * _a_b.
	 * 
	 * * * *
	 * 
	 * Also changed expected outcome for underscores. We treat
	 * them as converting to <i> instead of <em>.
	 * 
	 * * * *
	 * 
	 * *test  **test*  test**
	 * becomes
	 * <em>text **test</em> test**
	 * instead of
	 * *test  <strong>test*  test</strong>
	 * 
	 * _test  __test_  test__
	 * becomes
	 * <i>test  __test</i>  test__
	 * instead of
	 * _test  <strong>test_  test</strong>
	 * 
	 * * * *
	 * 
	 * Removed:
	 * ## Overlong emphasis
	 * Name: ____________  
	 * Organization: ____
	 * Region/Country: __
	 * _____Cut here_____
	 * ____Cut here____
	 * 
	 * * * *
	 * 
	 * Incorrect nesting; Changed expected outcome for:
	 * _test   _test_  test_
	 * __test __test__ test__
	 * **test **test** test**
	 * to
	 * <i>test   <i>test</i>  test</i>
	 * <strong>test <strong>test</strong> test</strong>
	 * <strong>test <strong>test</strong> test</strong>
	 * (because it is an example of correct, not incorrect, nesting)
	 */
	public function emphasis()
	{
		$this->createTestFor('Emphasis');
	}

	/**
	 * @test
	 */
	public function emptyListItem()
	{
		$this->createTestFor('EmptyListItem');
	}

	/**
	 * @test
	 * 
	 * A header after a paragraph needs to be preceded by a blank line. Eg:
	 * 
	 * Let's talk about track
	 * #8. It's the best on the cd.
	 * 
	 * This is a paragraph. There's no a header on the second starting
	 * with #8. I've changed the expected outcome to reflect this.
	 */
	public function headers()
	{
		$this->createTestFor('Headers');
	}

	/**
	 * @test
	 */
	public function horizontalRules()
	{
		$this->createTestFor('HorizontalRules');
	}

	/**
	 * @test
	 */
	public function inlineHTMLSimple()
	{
		$this->createTestFor('InlineHTMLSimple');
	}

	/**
	 * @test
	 */
	public function inlineHTMLSpan()
	{
		$this->createTestFor('InlineHTMLSpan');
	}

	/**
	 * @test
	 */
	public function inlineHTMLComments()
	{
		$this->createTestFor('InlineHTMLComments');
	}

	/**
	 * @test
	 */
	public function insAndDel()
	{
		$this->createTestFor('InsAndDel');
	}

	/**
	 * @test
	 */
	public function linksInlineStyle()
	{
		$this->createTestFor('LinksInlineStyle');
	}

	/**
	 * @test
	 */
	public function mD5Hashes()
	{
		$this->createTestFor('MD5Hashes');
	}

	/**
	 * @test
	 */
	public function mixedOLsAndULs()
	{
		$this->createTestFor('MixedOLsAndULs');
	}

	/**
	 * @test
	 */
	public function nesting()
	{
		$this->createTestFor('Nesting');
	}

	/**
	 * @test
	 */
	public function parensInURL()
	{
		$this->createTestFor('ParensInURL');
	}

	/**
	 * @test
	 * 
	 * Changed expected outcome: we allow all characters to be escaped: '\\'
	 * will become '\', '\"' will become '"' etc.
	 */
	public function PHPSpecificBugs()
	{
		$this->createTestFor('PHPSpecificBugs');
	}

	/**
	 * @test
	 * 
	 * Changed attribute order.
	 */
	public function quotesInAttributes()
	{
		$this->createTestFor('QuotesInAttributes');
	}

	/**
	 * @test
	 * 
	 * Changed:
	 * -expected outcome of blockquote following paragraph to one paragraph.
	 * -removed header as expected outcome
	 * since a paragraph ends with a blank line or an indented block. 
	 * 
	 * Same for the indented list: a list immediately following a paragraph
	 * without a blank line must be indented.
	 */
	public function tightBlocks()
	{
		$this->createTestFor('TightBlocks');
	}
}