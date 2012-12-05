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
		$doc = new \DOMDocument();
		$element = $doc->createElement('a', 'my email');
		$doc->appendChild($element);
		$element->setAttribute('href', 'mailto:me@example.com');
		$this->processor->process($doc);
		$this->assertEquals(
			$doc->saveXML($doc->documentElement),
			'<a href="&amp;#109;&amp;#97;&amp;#105;&amp;#108;&amp;#x74;&amp;#x6f;&amp;#x3a;m&amp;#101;&amp;#64;&amp;#101;&amp;#x78;&amp;#x61;&amp;#x6d;&amp;#x70;l&amp;#101;&amp;#46;&amp;#99;&amp;#x6f;&amp;#x6d;">&amp;#109;&amp;#x79;&amp;#32;&amp;#x65;&amp;#109;&amp;#x61;i&amp;#108;</a>'
		);
	}
}