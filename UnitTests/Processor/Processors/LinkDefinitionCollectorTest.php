<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Processor_Processors_LinkDefinitionCollectorTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->collector = new \AnyMark\Processor\Processors\LinkDefinitionCollector();
	}

	/**
	 * @test
	 */
	public function linkDefintionCollectorRemovesLinkDefinitionsFromText()
	{
		$text = "\n[linkDefinition]: http://example.com\n";
		$this->assertEquals(
			"\n",
			$this->collector->process($text)
		);
	}

	/**
	 * @test
	 */
	public function aLinkDefinitionIsSquareBracketsWithDefinitionFollowedBySemicolonAndUrl()
	{
		// given
		$text = "\n[linkDefinition]: http://example.com\n";

		// when
		$this->collector->process($text);

		// then
		$this->assertEquals(
			new \AnyMark\Pattern\Patterns\LinkDefinition(
				'linkDefinition', 'http://example.com'
			),
			$this->collector->get('linkDefinition')
		);

		$this->assertNull($this->collector->get('non existent definition'));
	}

	/**
	 * @test
	 */
	public function aTitleAttributeCanBeSpecifiedBetweenDoubleQuotes()
	{
		// given
		$text = "\n[linkDefinition]: http://example.com \"title\"\n";
	
		// when
		$this->collector->process($text);
	
		// then
		$this->assertEquals(
			new \AnyMark\Pattern\Patterns\LinkDefinition(
				'linkDefinition', 'http://example.com', 'title'
			),
			$this->collector->get('linkDefinition')
		);
	}

	/**
	 * @test
	 */
	public function aTitleAttributeCanBeSpecifiedBetweenSingleQuotes()
	{
		// given
		$text = "\n[linkDefinition]: http://example.com 'title'\n";
	
		// when
		$this->collector->process($text);
	
		// then
		$this->assertEquals(
			new \AnyMark\Pattern\Patterns\LinkDefinition(
				'linkDefinition', 'http://example.com', 'title'
			),
			$this->collector->get('linkDefinition')
		);
	}

	/**
	 * @test
	 */
	public function aTitleAttributeCanBeSpecifiedBetweenDoubleParentheses()
	{
		// given
		$text = "\n[linkDefinition]: http://example.com (title)\n";
	
		// when
		$this->collector->process($text);
	
		// then
		$this->assertEquals(
			new \AnyMark\Pattern\Patterns\LinkDefinition(
				'linkDefinition', 'http://example.com', 'title'
			),
			$this->collector->get('linkDefinition')
		);
	}

	/**
	 * @test
	 */
	public function urlCanBePlacedBetweenAngleBrackets()
	{
		// given
		$text = "\n[linkDefinition]: <http://example.com>\n";
	
		// when
		$this->collector->process($text);
	
		// then
		$this->assertEquals(
			new \AnyMark\Pattern\Patterns\LinkDefinition(
				'linkDefinition', 'http://example.com'
			),
			$this->collector->get('linkDefinition')
		);
	}

	/**
	 * @test
	 */
	public function aLinkDefinitionMustBePlacedOnItsOwnLine()
	{
		$text = "\ntext [linkDefinition]: http://example.com\n";
		$this->assertEquals(
			"\ntext [linkDefinition]: http://example.com\n",
			$this->collector->process($text)
		);
	}

	/**
	 * @test
	 */
	public function titleAttributeCanBePlacedOnNextLine()
	{
		$text = "\n[linkDefinition]: http://example.com\n'title'\n";
		$this->collector->process($text);
		$this->assertEquals(
			new \AnyMark\Pattern\Patterns\LinkDefinition(
				'linkDefinition', 'http://example.com', 'title'
			),
			$this->collector->get('linkDefinition')
		);
	}

	/**
	 * @test
	 */
	public function titleAttributeCanBePlacedIndentedOnNextLine()
	{
		$text = "\n[linkDefinition]: http://example.com\n\t'title'\n";
		$this->collector->process($text);
		$this->assertEquals(
			new \AnyMark\Pattern\Patterns\LinkDefinition(
				'linkDefinition', 'http://example.com', 'title'
			),
			$this->collector->get('linkDefinition')
		);
	}
}