<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Plugins_EscapeRestorerTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->eventMapper = new \AnyMark\UnitTests\Support\EventMapperMock();
		$this->plugin = new \AnyMark\Plugins\EscapeRestorer();

		$this->plugin->register($this->eventMapper);
	}

	/**
	 * @test
	 */
	public function restoresEscapedInText()
	{
		$tree = new \ElementTree\ElementTree();
		$text = $tree->createText('foo \* bar');
		$tree->append($text);

		$callback = $this->eventMapper->getCallback();
		$event = new \AnyMark\Events\AfterParsing($tree);
		$callback($event);

		$this->assertEquals('foo * bar', $event->getTree()->toString());
	}

	/**
	 * @test
	 */
	public function restoresEscapedInAttributeValues()
	{
		$tree = new \ElementTree\ElementTree();
		$div = $tree->createElement('div');
		$div->setAttribute('id', 'foo \* bar');
		$tree->append($div);

		$callback = $this->eventMapper->getCallback();
		$event = new \AnyMark\Events\AfterParsing($tree);
		$callback($event);

		$this->assertEquals('<div id="foo * bar" />', $event->getTree()->toString());
	}

	/**
	 * @test
	 */
	public function doesntRestoreInCode()
	{
		$tree = new \ElementTree\ElementTree();
		$div = $tree->createElement('code');
		$text = $tree->createText('foo \* bar');
		$tree->append($div);
		$div->append($text);

		$callback = $this->eventMapper->getCallback();
		$event = new \AnyMark\Events\AfterParsing($tree);
		$callback($event);

		$this->assertEquals('<code>foo \* bar</code>', $event->getTree()->toString());
	}
}