<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Processor_Processors_EmptyLineFixerTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->pattern = new \AnyMark\Processor\Processors\EmptyLineFixer();
	}

	/**
	 * @test
	 */
	public function lineWithOnlySpacesAndOrTabsIsCleaned()
	{
		$text = "para\n \t\npara";
		$html = "para\n\npara";
		$this->assertEquals($html, $this->pattern->process($text));
	}
}