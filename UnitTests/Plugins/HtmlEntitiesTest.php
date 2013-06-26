<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Plugins_HtmlEntitiesTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->eventMapper = new \AnyMark\UnitTests\Support\EventMapperMock();
		$this->plugin = new \AnyMark\Plugins\HtmlEntities();

		$this->plugin->register($this->eventMapper);
	}

	/**
	 * @test
	 */
	public function replacesEntitiesInText()
	{
		$tree = new \ElementTree\ElementTree();
		$text = $tree->createText('at&t');
		$tree->append($text);

		$callback = $this->eventMapper->getCallback();
		$event = new \AnyMark\Events\AfterParsing($tree);
		$callback($event);

		$this->assertEquals('at&amp;t', $tree->toString());
	}

	/**
	 * @test
	 */
	public function replacesEntitiesInAttributeValues()
	{
		$tree = new \ElementTree\ElementTree();
		$div = $tree->createElement('div');
		$div->setAttribute('id', 'at&t');
		$tree->append($div);
	
		$callback = $this->eventMapper->getCallback();
		$event = new \AnyMark\Events\AfterParsing($tree);
		$callback($event);
	
		$this->assertEquals('<div id="at&amp;t" />', $tree->toString());
	}
}