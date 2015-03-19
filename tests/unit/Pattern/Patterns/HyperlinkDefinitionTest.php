<?php

namespace Anymark;

class AnyMark_Pattern_Patterns_HyperlinkDefinitionTest extends PatternReplacementAssertions
{
	public function setup()
	{
		$this->hyperlinkDef = new \AnyMark\Pattern\Patterns\HyperlinkDefinition();
	}

	public function getPattern()
	{
		return $this->hyperlinkDef;
	}

	/**
	 * @test
	 */
	public function matchesHyperlinkDefinition()
	{
		$text = "\n[linkDefinition]: http://example.com\n";

		$this->assertEquals(
			$this->elementTree()->createText(''), $this->applyPattern($text)
		);
	}

	/**
	 * @test
	 */
	public function savesHyperlinkDefinition()
	{
		$text = "\n[linkDefinition]: http://example.com\n";

		$this->applyPattern($text);

		$this->assertEquals(
			new \AnyMark\Pattern\Patterns\LinkDefinition(
				'linkDefinition', 'http://example.com'
			),
			$this->hyperlinkDef->get('linkDefinition')
		);
	}

	/**
	 * @test
	 */
	public function aTitleAttributeCanBeSpecifiedBetweenDoubleQuotes()
	{
		$text = "\n[linkDefinition]: http://example.com \"title\"\n";

		$this->applyPattern($text);

		$this->assertEquals(
			new \AnyMark\Pattern\Patterns\LinkDefinition(
				'linkDefinition', 'http://example.com', 'title'
			),
			$this->hyperlinkDef->get('linkDefinition')
		);
	}

	/**
	 * @test
	 */
	public function aTitleAttributeCanBeSpecifiedBetweenSingleQuotes()
	{
		$text = "\n[linkDefinition]: http://example.com 'title'\n";

		$this->applyPattern($text);

		$this->assertEquals(
			new \AnyMark\Pattern\Patterns\LinkDefinition(
				'linkDefinition', 'http://example.com', 'title'
			),
			$this->hyperlinkDef->get('linkDefinition')
		);
	}

	/**
	 * @test
	 */
	public function aTitleAttributeCanBeSpecifiedBetweenDoubleParentheses()
	{
		$text = "\n[linkDefinition]: http://example.com (title)\n";

		$this->applyPattern($text);

		$this->assertEquals(
			new \AnyMark\Pattern\Patterns\LinkDefinition(
				'linkDefinition', 'http://example.com', 'title'
			),
			$this->hyperlinkDef->get('linkDefinition')
		);
	}

	/**
	 * @test
	 */
	public function urlCanBePlacedBetweenAngleBrackets()
	{
		$text = "\n[linkDefinition]: <http://example.com>\n";

		$this->applyPattern($text);

		$this->assertEquals(
			new \AnyMark\Pattern\Patterns\LinkDefinition(
				'linkDefinition', 'http://example.com'
			),
			$this->hyperlinkDef->get('linkDefinition')
		);
	}

	/**
	 * @test
	 */
	public function aLinkDefinitionMustBePlacedOnItsOwnLine()
	{
		$text = "\ntext [linkDefinition]: http://example.com\n";

		$this->assertEquals(null, $this->hyperlinkDef->get('linkDefinition'));
	}

	/**
	 * @test
	 */
	public function titleAttributeCanBePlacedOnNextLine()
	{
		$text = "\n[linkDefinition]: http://example.com\n'title'\n";

		$this->applyPattern($text);

		$this->assertEquals(
			new \AnyMark\Pattern\Patterns\LinkDefinition(
				'linkDefinition', 'http://example.com', 'title'
			),
			$this->hyperlinkDef->get('linkDefinition')
		);
	}

	/**
	 * @test
	 */
	public function titleAttributeCanBePlacedIndentedOnNextLine()
	{
		$text = "\n[linkDefinition]: http://example.com\n\t'title'\n";

		$this->applyPattern($text);

		$this->assertEquals(
			new \AnyMark\Pattern\Patterns\LinkDefinition(
				'linkDefinition', 'http://example.com', 'title'
			),
			$this->hyperlinkDef->get('linkDefinition')
		);
	}

	/**
	 * @test
	 */
	public function urlCanHaveSpaces()
	{
		$text = "\n[definition]: <url://with space>\n";

		$this->applyPattern($text);

		$this->assertEquals(
			new \AnyMark\Pattern\Patterns\LinkDefinition(
				'definition', 'url://with space'
			),
			$this->hyperlinkDef->get('definition')
		);
	}
}