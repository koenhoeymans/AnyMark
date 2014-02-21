<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Processor_Processors_NewLineStandardizer_NewLineStandardizerRegistrarTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function registersForBeforeParsingEvent()
	{
		$eventMapper = $this->getMock('Epa\\EventMapper');
		$eventMapper
			->expects($this->once())
			->method('registerForEvent')
			->with('BeforeParsingEvent', function() {});

		$registrar = new \AnyMark\Plugins\NewLineStandardizer\NewLineStandardizerRegistrar();
		$registrar->register($eventMapper);
	}
}