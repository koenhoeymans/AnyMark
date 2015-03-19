<?php

namespace AnyMark\Plugins\EmailObfuscator;

class EmailObfuscatorRegistrarTest extends \PHPUnit_Framework_TestCase
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