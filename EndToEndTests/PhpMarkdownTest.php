<?php

require_once('TestHelper.php');

/**
 * These are the PHPMarkdown tests as found in the test suite of PHPMarkdown.
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
				. DIRECTORY_SEPARATOR . $name . '.xhtml'
			)),
			$this->tidy($parsedText)
		);
	}

	/**
	 * @test
	 */
	public function adjacentLists()
	{
		$this->createTestFor('Adjacent Lists');
	}

	/**
	 * @test
	 */
	public function autoLinks()
	{
		$this->createTestFor('Auto Links');
	}

	/**
	 * @test
	 */
	public function backslashEscapes()
	{
		$this->createTestFor('Backslash escapes');
	}

	/**
	 * @test
	 */
	public function codeBlockInAListItem()
	{
		$this->createTestFor('Code block in a list item');
	}

	/**
	 * @test
	 */
	public function codeBlockOnSecondLine()
	{
		$this->createTestFor('Code block on second line');
	}

	/**
	 * @test
	 */
	public function codeBlockRegressions()
	{
		$this->createTestFor('Code block regressions');
	}

	/**
	 * @test
	 */
	public function codeSpans()
	{
		$this->createTestFor('Code Spans');
	}

	/**
	 * @test
	 */
	public function emailAutoLinks()
	{
		$this->createTestFor('Email auto links');
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
		$this->createTestFor('Empty List Item');
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
		$this->createTestFor('Horizontal Rules');
	}

	/**
	 * @test
	 */
	public function inlineHTMLSimple()
	{
		$this->createTestFor('Inline HTML (Simple)');
	}

	/**
	 * @test
	 */
	public function inlineHTMLSpan()
	{
		$this->createTestFor('Inline HTML (Span)');
	}

	/**
	 * @test
	 */
	public function inlineHTMLComments()
	{
		$this->createTestFor('Inline HTML comments');
	}

	/**
	 * @test
	 */
	public function insAndDel()
	{
		$this->createTestFor('Ins & del');
	}

	/**
	 * @test
	 */
	public function linksInlineStyle()
	{
		$this->createTestFor('Links, inline style');
	}

	/**
	 * @test
	 */
	public function mD5Hashes()
	{
		$this->createTestFor('MD5 Hashes');
	}

	/**
	 * @test
	 */
	public function mixedOLsAndULs()
	{
		$this->createTestFor('Mixed OLs and ULs');
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
		$this->createTestFor('Parens in URL');
	}

	/**
	 * @test
	 */
	public function PHPSpecificBugs()
	{
		$this->createTestFor('PHP-Specific Bugs');
	}

	/**
	 * @test
	 */
	public function quotesInAttributes()
	{
		$this->createTestFor('Quotes in attributes');
	}

	/**
	 * @test
	 */
	public function tightBlocks()
	{
		$this->createTestFor('Tight blocks');
	}

	/**
	 * @test
	 */
	public function XMLEmptyTag()
	{
		$this->createTestFor('XML empty tag');
	}
}