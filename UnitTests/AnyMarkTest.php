<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

use \AnyMark\UnitTests\Support;

class AnyMark_PAnyMarkTest extends PHPUnit_Framework_TestCase
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

	/**
	 * @test
	 */
	public function returnsParsingResultAsDomDocument()
	{
		$this->assertTrue($this->anyMark->parse('text') instanceof \DomDocument);
	}

	/**
	 * @test
	 */
	public function savesDomResultToStringInXmlFormat()
	{
		$domDoc = new \DOMDocument();
		$domDoc->loadXML('<doc>text</doc>');

		$this->assertEquals(
			'<doc>text</doc>', $this->anyMark->saveXml($domDoc)
		);
	}
}