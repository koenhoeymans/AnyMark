<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Plugins_HtmlEntities_HtmlEntitiesRegistrarTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function registersCallbackForAfterParsingEvent()
	{
		$eventDispatcher = $this->getMock('Epa\\Api\\EventDispatcher');
		$eventDispatcher
			->expects($this->once())
			->method('registerForEvent')
			->with('AnyMark\\PublicApi\\AfterParsingEvent', function() {});

		$registrar = new \AnyMark\Plugins\HtmlEntities\HtmlEntitiesRegistrar();
		$registrar->registerHandlers($eventDispatcher);
	}
}