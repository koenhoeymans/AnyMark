<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

use \AnyMark\UnitTests\Support;

class AnyMark_PAnyMarkTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->anyMark = \AnyMark\AnyMark::setup();
	}

	/**
	 * @test
	 */
	public function beforeProcessingPreTextProcessorsAreCalled()
	{
		$preProcessor = $this->getMock('\\AnyMark\\Processor\\TextProcessor');
		$preProcessor
			->expects($this->atLeastOnce())
			->method('process')
			->with("text\n");

		$this->anyMark->addPreTextProcessor($preProcessor);
		$this->anyMark->parse('text');
	}

	/**
	 * @test
	 */
	public function afterProcessingPostDomProcessorsAreCalled()
	{
		$preProcessor = $this->getMock('\\AnyMark\\Processor\\DomProcessor');
		$preProcessor
		->expects($this->atLeastOnce())
		->method('process');
	
		$this->anyMark->addPostDomProcessor($preProcessor);
		$this->anyMark->parse('text');
	}
}