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

		$parsedText = $anyMark->parse(file_get_contents(
			__DIR__
			. DIRECTORY_SEPARATOR . 'PhpMarkdown.mdtest'
			. DIRECTORY_SEPARATOR . $name . '.text'
		))->toString();

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
	 */
	public function tightBlocks()
	{
		$this->createTestFor('TightBlocks');
	}
}