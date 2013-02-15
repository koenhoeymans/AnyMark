<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_ElementTree_TextTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->elTree = $this->getMockBuilder('AnyMark\\Component\\Component')
			->getMock();
	}
	/**
	 * @test
	 */
	public function hasValue()
	{
		$text = new \AnyMark\ElementTree\Text('foo');

		$this->assertEquals('foo', $text->getValue());
	}

	/**
	 * @test
	 */
	public function returnsValueForXmlUse()
	{
		$text = new \AnyMark\ElementTree\Text('foo');

		$this->assertEquals('foo', $text->saveXmlStyle());
	}
}