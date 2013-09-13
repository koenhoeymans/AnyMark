<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Plugins_DetabTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->eventMapper = new \AnyMark\UnitTests\Support\EventMapperMock();
		$this->plugin = new \AnyMark\Plugins\Detab();

		$this->plugin->register($this->eventMapper);
	}

	/**
	 * @test
	 */
	public function registersForBeforeParsingEvent()
	{
		$this->assertEquals('BeforeParsingEvent', $this->eventMapper->getEvent());
	}

	/**
	 * @test
	 */
	public function replacesTabBySpaces()
	{
		$callback = $this->eventMapper->getCallback();
		$event = new \AnyMark\Events\BeforeParsing("para\n\t\npara");
		$callback($event);

		$this->assertEquals(
			"para\n    \npara", $event->getText()
		);
	}
}