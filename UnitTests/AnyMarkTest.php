<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

use \AnyMark\UnitTests\Support;

class AnyMark_AnyMarkTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->anyMark = \AnyMark\AnyMark::setup();
	}

	/**
	 * @test
	 */
	public function eventsAreThrown()
	{
		$observer = $this->getMock('\\Epa\\Observer');
		$this->anyMark->addObserver($observer);

		$observer
			->expects($this->exactly(3))
			->method('notify');

		$this->anyMark->parse('text');
	}

	/**
	 * @test
	 */
	public function returnsParsingResultAsElementTree()
	{
		$this->assertTrue(
			$this->anyMark->parse('text') instanceof \ElementTree\ElementTree
		);
	}
}