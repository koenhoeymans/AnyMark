<?php

use ElementTree\ElementTreeElement;

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Plugins_EscapeRestorer_EscapeRestorerRegistrarTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function registersForAfterParsingEvent()
	{
		$eventMapper = $this->getMock('Epa\\EventMapper');
		$eventMapper
			->expects($this->at(0))
			->method('registerForEvent')
			->with('AfterParsingEvent', function() {});
		
		$registrar = new \AnyMark\Plugins\EscapeRestorer\EscapeRestorerRegistrar();
		$registrar->register($eventMapper);
	}

	/**
	 * @test
	 */
	public function registersForPatternMatch()
	{
		$eventMapper = $this->getMock('Epa\\EventMapper');
		$eventMapper
			->expects($this->at(1))
			->method('registerForEvent')
			->with('PatternMatch', function() {});
		
		$registrar = new \AnyMark\Plugins\EscapeRestorer\EscapeRestorerRegistrar();
		$registrar->register($eventMapper);
	}
}