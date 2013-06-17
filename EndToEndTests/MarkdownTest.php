<?php

require_once('TestHelper.php');

class AnyMark_EndToEndTests_MarkdownTest extends \AnyMark\EndToEndTests\Support\Tidy
{
	public function createTestFor($name)
	{
		$anyMark = \AnyMark\AnyMark::setup();

		$parsedText = $anyMark->parse(file_get_contents(
			__DIR__
			. DIRECTORY_SEPARATOR . 'Markdown.mdtest'
			. DIRECTORY_SEPARATOR . $name . '.text'
		))->toString();

		$this->assertEquals(
			$this->tidy(file_get_contents(
				__DIR__
				. DIRECTORY_SEPARATOR . 'Markdown.mdtest'
				. DIRECTORY_SEPARATOR . $name . '.xhtml'
			)),
			$this->tidy($parsedText)
		);
	}

	/**
	 * @test
	 */
	public function ampsAndAngleEncoding()
	{
		$this->createTestFor('Amps and angle encoding');
	}

	/**
	 * @test
	 */
	public function autoLinks()
	{
		$this->createTestFor('Auto links');
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
	public function blockquotesWithCodeBlocks()
	{
		$this->createTestFor('Blockquotes with code blocks');
	}

	/**
	 * @test
	 */
	public function codeBlocks()
	{
		$this->createTestFor('Code Blocks');
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
	public function hardWrappedParagraphsWithListLikeLines()
	{
		$this->createTestFor('Hard-wrapped paragraphs with list-like lines');
	}

	/**
	 * @test
	 */
	public function horizontalRules()
	{
		$this->createTestFor('Horizontal rules');
	}

	/**
	 * @test
	 */
	public function images()
	{
		$this->createTestFor('Images');
	}

	/**
	 * @test
	 */
	public function inlineHTMLAdvanced()
	{
		$this->createTestFor('Inline HTML (Advanced)');
	}

	/**
	 * @test
	 */
	public function inlineHTMLSimple()
	{
		$this->createTestFor('Inline HTML (Advanced)');
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
	public function linksInlineStyle()
	{
		$this->createTestFor('Links, inline style');
	}

	/**
	 * @test
	 */
	public function linksReferenceStyle()
	{
		$this->createTestFor('Links, reference style');
	}

	/**
	 * @test
	 */
	public function linksShortcutReferences()
	{
		$this->createTestFor('Links, shortcut references');
	}

	/**
	 * @test
	 */
	public function literalQuotesInTitles()
	{
		$this->createTestFor('Literal quotes in titles');
	}

	/**
	 * @test
	 * 
	 */
	public function markdownDocumentationBasics()
	{
		$this->createTestFor('Markdown Documentation - Basics');
	}

	/**
	 * @test
	 * 
	 */
	public function markdownDocumentationSyntax()
	{
		$this->createTestFor('Markdown Documentation - Syntax');
	}

	/**
	 * @test
	 */
	public function nestedBlockquotes()
	{
		$this->createTestFor('Nested blockquotes');
	}

	/**
	 * @test
	 */
	public function orderedAndUnorderedLists()
	{
		$this->createTestFor('Ordered and unordered lists');
	}

	/**
	 * @test
	 */
	public function strongAndEmTogether()
	{
		$this->createTestFor('Strong and em together');
	}

	/**
	 * @test
	 */
	public function tabs()
	{
		$this->createTestFor('Tabs');
	}

	/**
	 * @test
	 */
	public function tidyness()
	{
		$this->createTestFor('Tidyness');
	}
}