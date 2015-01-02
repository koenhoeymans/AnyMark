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
		$eventDispatcher = $this->getMock('Epa\\Api\\EventDispatcher');
		$eventDispatcher
			->expects($this->once())
			->method('registerForEvent')
			->with('AnyMark\\PublicApi\\BeforeParsingEvent', function() {});

		$registrar = new \AnyMark\Plugins\NewLineStandardizer\NewLineStandardizerRegistrar();
		$registrar->registerHandlers($eventDispatcher);
	}
}