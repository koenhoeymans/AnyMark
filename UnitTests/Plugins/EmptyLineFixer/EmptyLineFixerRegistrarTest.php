<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Plugins_EmptyLineFixer_EmptyLineFixerRegistrarTest extends PHPUnit_Framework_TestCase
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
		
		$registrar = new \AnyMark\Plugins\EmptyLineFixer\EmptyLineFixerRegistrar();
		$registrar->register($eventMapper);
	}
}