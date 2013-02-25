<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

use \AnyMark\UnitTests\Support;

class AnyMark_AnyMarkTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->anyMark = \AnyMark\AnyMark::setup()->get('AnyMark\AnyMark');
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
			->with("text\n\n");

		$this->anyMark->addPreTextProcessor($preProcessor);
		$this->anyMark->parse('text');
	}

	/**
	 * @test
	 */
	public function afterProcessingPostDomProcessorsAreCalled()
	{
		$preProcessor = $this->getMock('\\AnyMark\\Processor\\ElementTreeProcessor');
		$preProcessor
			->expects($this->atLeastOnce())
			->method('process');
	
		$this->anyMark->addPostElementTreeProcessor($preProcessor);
		$this->anyMark->parse('text');
	}

	/**
	 * @test
	 */
	public function returnsParsingResultAsElementTree()
	{
		$this->assertTrue($this->anyMark->parse('text') instanceof \ElementTree\ElementTree);
	}

	/**
	 * @test
	 */
	public function customIniFileCanBeSpecified()
	{
		// @todo refactor
		$this->anyMark->setPatternsIni('dummy.ini');
	}
}