<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Processor_Processors_EmailObfuscatorTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->processor = new \AnyMark\Processor\Processors\EmailObfuscator();
	}

	/**
	 * @test
	 */
	public function encodesEmail()
	{
		$element = new \AnyMark\ComponentTree\Element('a');
		$text = new \AnyMark\ComponentTree\Text('my email');
		$element->append($text);
		$element->setAttribute('href', 'mailto:me@example.com');
		$this->processor->process($element);
		$this->assertEquals(
			'<a href="&#x6d;&#97;&#x69;&#108;&#x74;&#111;&#x3a;&#109;a&#x69;&#108;&#x74;&#111;&#x3a;&#109;&#x65;&#64;e&#x78;&#97;&#x6d;&#112;&#x6c;&#101;&#x2e;&#x63;&#111;&#x6d;">&#109;&#x79;&#32;&#x65;&#109;&#x61;i&#108;</a>',
			$element->saveXMLStyle()
		);
	}
}