<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Plugins_EmailObfuscatorTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->eventMapper = new \AnyMark\UnitTests\Support\EventMapperMock();
		$this->plugin = new \AnyMark\Plugins\EmailObfuscator();

		$this->plugin->register($this->eventMapper);
	}

	/**
	 * @test
	 */
	public function registersForAfterParsingEvent()
	{
		$this->assertEquals('AfterParsingEvent', $this->eventMapper->getEvent());
	}

	/**
	 * @test
	 */
	public function encodesEmail()
	{
		$tree = new \ElementTree\ElementTree();
		$element = new \ElementTree\ElementTreeElement('a');
		$tree->append($element);
		$text = new \ElementTree\ElementTreeText('my email');
		$element->append($text);
		$element->setAttribute('href', 'mailto:me@example.com');

		$callback = $this->eventMapper->getCallback();
		$event = new \AnyMark\Events\AfterParsing($tree);
		$callback($event);

		$this->assertEquals(
			'<a href="&#109;&#97;&#105;&#108;&#x74;&#x6f;&#x3a;m&#101;&#64;&#101;&#x78;&#x61;&#x6d;&#x70;l&#101;&#46;&#99;&#x6f;&#x6d;">&#x6d;&#121;&#32;&#x65;&#109;&#x61;&#105;&#108;</a>',
			$element->toString()
		);
	}
}