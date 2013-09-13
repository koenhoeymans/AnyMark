<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Processor_Processors_NewLineStandardizerTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->eventMapper = new \AnyMark\UnitTests\Support\EventMapperMock();
		$this->plugin = new \AnyMark\Plugins\NewLineStandardizer();

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
	public function allLineEndingsShouldBeUnixStandard()
	{
		$callback = $this->eventMapper->getCallback();
		$event = new \AnyMark\Events\BeforeParsing("para\rline\r\nother");
		$callback($event);

		$this->assertEquals(
			"para\nline\nother", $event->getText()
		);
	}
}