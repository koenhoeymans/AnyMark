<?php

require_once('TestHelper.php');

/**
 * Changed expected outcome for headers, adding id's.
 */
class AnyMark_EndToEndTests_MarkdownTest extends \AnyMark\EndToEndTests\Support\Tidy
{
	public function createTestFor($name)
	{
		$fjor = \AnyMark\AnyMark::setup();
		$fjor
			->given('AnyMark\\Util\\InternalUrlBuilder')
			->thenUse('AnyMark\\Util\\ExtensionlessUrlBuilder');
		$anyMark = $fjor->get('AnyMark\\AnyMark');

		$parsedText = $anyMark->parse(file_get_contents(
			__DIR__
			. DIRECTORY_SEPARATOR . 'Markdown.mdtest'
			. DIRECTORY_SEPARATOR . $name . '.text'
		));

		$this->assertEquals(
			$this->tidy(file_get_contents(
				__DIR__
				. DIRECTORY_SEPARATOR . 'Markdown.mdtest'
				. DIRECTORY_SEPARATOR . $name . '.html'
			)),
			$this->tidy($parsedText)
		);
	}

	/**
	 * @test
	 * 
	 * Changed <p>6 > 5.</p> to <p>6 &gt; 5.</p> as expected outcome
	 */
	public function ampsAndAngleEncoding()
	{
		$this->createTestFor('AmpsAndAngleEncoding');
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
	 * changed
	 * <span attr='`ticks`'>
	 * <span attr='\\backslashes\\'>
	 * to
	 * <span attr="`ticks`">
	 * <span attr="\\backslashes\\">
	 * as expected outcome
	 */
	public function backslashEscapes()
	{
		$this->createTestFor('BackslashEscapes');
	}

	/**
	 * @test
	 */
	public function blockquotesWithCodeBlocks()
	{
		$this->createTestFor('BlockquotesWithCodeBlocks');
	}

	/**
	 * @test
	 */
	public function codeBlocks()
	{
		$this->createTestFor('CodeBlocks');
	}

	/**
	 * @test
	 * changed
	 * <span attr='`ticks`'>
	 * to
	 * <span attr="`ticks`">
	 * as expected outcome
	 */
	public function codeSpans()
	{
		$this->createTestFor('CodeSpans');
	}

	/**
	 * @test
	 */
	public function hardWrappedParagraphsWithListLikeLines()
	{
		$this->createTestFor('HardWrappedParagraphsWithListLikeLines');
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
	 * 
	 * Changed place of src attribute.
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
		$this->createTestFor('InlineHTMLAdvanced');
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
	public function inlineHTMLComments()
	{
		$this->createTestFor('InlineHTMLComments');
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
	public function linksReferenceStyle()
	{
		$this->createTestFor('LinksReferenceStyle');
	}

	/**
	 * @test
	 */
	public function linksShortcutReferences()
	{
		$this->createTestFor('LinksShortcutReferences');
	}

	/**
	 * @test
	 */
	public function literalQuotesInTitles()
	{
		$this->createTestFor('LiteralQuotesInTitles');
	}

	/**
	 * @xxtest
	 * 
	 */
	public function markdownDocumentationBasics()
	{
		$this->createTestFor('MarkdownDocumentationBasics');
	}

	/**
	 * @xxtest
	 * 
	 */
	public function markdownDocumentationSyntax()
	{
		$this->createTestFor('MarkdownDocumentationSyntax');
	}

	/**
	 * @test
	 * 
	 * Removed indentation within the blockquote.
	 */
	public function nestedBlockquotes()
	{
		$this->createTestFor('NestedBlockquotes');
	}

	/**
	 * @test
	 * 
	 * Changed:
	 * *	Tab
	 *		*	Tab
	 *	 		*	Tab
	 * to:
	 * *	Tab
	 * 		 *	Tab and space
	 * 			 * Tab and space
	 * 
	 * This follows the difference with Markdown that list can be placed after text
	 * if it is indented, regardless of the list level. With Markdown indentation
	 * is not necessary in nested lists, but the first level must have a blank line.
	 * In my implementation there must not be a blank line in the first level, but
	 * all levels must have indentation if there is no blank line (indentation not
	 * necessary if there is a blank line).
	 */
	public function orderedAndUnorderedLists()
	{
		$this->createTestFor('OrderedAndUnorderedLists');
	}

	/**
	 * @test
	 * 
	 * Removed the underscore tests since I use a different implementation.
	 */
	public function strongAndEmTogether()
	{
		$this->createTestFor('StrongAndEmTogether');
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
	 * 
	 * Added space before <ul> as expected outcome.
	 */
	public function tidyness()
	{
		$this->createTestFor('Tidyness');
	}
}