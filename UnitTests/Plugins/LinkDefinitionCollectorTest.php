<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Plugins_LinkDefinitionCollectorTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->eventMapper = new \AnyMark\UnitTests\Support\EventMapperMock();
		$this->plugin = new \AnyMark\Plugins\LinkDefinitionCollector();

		$this->plugin->register($this->eventMapper);
	}

	/**
	 * @test
	 */
	public function registersForBeforeParsingEvent()
	{
		$this->assertEquals(
			'AnyMark\\Events\\BeforeParsing', $this->eventMapper->getEvent()
		);
	}

	/**
	 * @test
	 */
	public function linkDefintionCollectorRemovesLinkDefinitionsFromText()
	{
		$text = "\n[linkDefinition]: http://example.com\n";
		$callback = $this->eventMapper->getCallback();
		$event = new \AnyMark\Events\BeforeParsing($text);
		$callback($event);

		$this->assertEquals("\n", $event->getText());
	}

	/**
	 * @test
	 */
	public function aLinkDefinitionIsSquareBracketsWithDefinitionFollowedBySemicolonAndUrl()
	{
		$text = "\n[linkDefinition]: http://example.com\n";
		$callback = $this->eventMapper->getCallback();
		$event = new \AnyMark\Events\BeforeParsing($text);
		$callback($event);
		
		$this->assertEquals(
			new \AnyMark\Pattern\Patterns\LinkDefinition(
					'linkDefinition', 'http://example.com'
			),
			$this->plugin->get('linkDefinition')
		);
	
		$this->assertNull($this->plugin->get('non existent definition'));
	}

	/**
	 * @test
	 */
	public function aTitleAttributeCanBeSpecifiedBetweenDoubleQuotes()
	{
		$text = "\n[linkDefinition]: http://example.com \"title\"\n";
		$callback = $this->eventMapper->getCallback();
		$event = new \AnyMark\Events\BeforeParsing($text);
		$callback($event);

		$this->assertEquals(
			new \AnyMark\Pattern\Patterns\LinkDefinition(
				'linkDefinition', 'http://example.com', 'title'
			),
			$this->plugin->get('linkDefinition')
		);
	}

	/**
	 * @test
	 */
	public function aTitleAttributeCanBeSpecifiedBetweenSingleQuotes()
	{
		$text = "\n[linkDefinition]: http://example.com 'title'\n";
		$callback = $this->eventMapper->getCallback();
		$event = new \AnyMark\Events\BeforeParsing($text);
		$callback($event);

		$this->assertEquals(
			new \AnyMark\Pattern\Patterns\LinkDefinition(
				'linkDefinition', 'http://example.com', 'title'
			),
			$this->plugin->get('linkDefinition')
		);
	}

	/**
	 * @test
	 */
	public function aTitleAttributeCanBeSpecifiedBetweenDoubleParentheses()
	{
		$text = "\n[linkDefinition]: http://example.com (title)\n";
		$callback = $this->eventMapper->getCallback();
		$event = new \AnyMark\Events\BeforeParsing($text);
		$callback($event);

		$this->assertEquals(
			new \AnyMark\Pattern\Patterns\LinkDefinition(
				'linkDefinition', 'http://example.com', 'title'
			),
			$this->plugin->get('linkDefinition')
		);
	}

	/**
	 * @test
	 */
	public function urlCanBePlacedBetweenAngleBrackets()
	{
		$text = "\n[linkDefinition]: <http://example.com>\n";
		$callback = $this->eventMapper->getCallback();
		$event = new \AnyMark\Events\BeforeParsing($text);
		$callback($event);

		$this->assertEquals(
			new \AnyMark\Pattern\Patterns\LinkDefinition(
					'linkDefinition', 'http://example.com'
			),
			$this->plugin->get('linkDefinition')
		);
	}

	/**
	 * @test
	 */
	public function aLinkDefinitionMustBePlacedOnItsOwnLine()
	{
		$text = "\ntext [linkDefinition]: http://example.com\n";
		$callback = $this->eventMapper->getCallback();
		$event = new \AnyMark\Events\BeforeParsing($text);
		$callback($event);

		$this->assertEquals(
			"\ntext [linkDefinition]: http://example.com\n",
			$event->getText()
		);
	}

	/**
	 * @test
	 */
	public function titleAttributeCanBePlacedOnNextLine()
	{
		$text = "\n[linkDefinition]: http://example.com\n'title'\n";
		$callback = $this->eventMapper->getCallback();
		$event = new \AnyMark\Events\BeforeParsing($text);
		$callback($event);

		$this->assertEquals(
			new \AnyMark\Pattern\Patterns\LinkDefinition(
					'linkDefinition', 'http://example.com', 'title'
			),
			$this->plugin->get('linkDefinition')
		);
	}

	/**
	 * @test
	 */
	public function titleAttributeCanBePlacedIndentedOnNextLine()
	{
		$text = "\n[linkDefinition]: http://example.com\n\t'title'\n";
		$callback = $this->eventMapper->getCallback();
		$event = new \AnyMark\Events\BeforeParsing($text);
		$callback($event);

		$this->assertEquals(
			new \AnyMark\Pattern\Patterns\LinkDefinition(
					'linkDefinition', 'http://example.com', 'title'
			),
			$this->plugin->get('linkDefinition')
		);
	}
}