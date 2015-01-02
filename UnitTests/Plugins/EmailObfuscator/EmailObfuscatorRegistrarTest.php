<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Plugins_EmailObfuscator_EmailObfuscatorRegistrarTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function registersForAfterParsingEvent()
	{
		$eventDispatcher = $this->getMock('Epa\\Api\\EventDispatcher');
		$eventDispatcher
			->expects($this->once())
			->method('registerForEvent')
			->with('AnyMark\\PublicApi\\AfterParsingEvent', function() {});

		$registrar = new \AnyMark\Plugins\EmailObfuscator\EmailObfuscatorRegistrar();
		$registrar->registerHandlers($eventDispatcher);
	}
}