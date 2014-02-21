<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Processor_Processors_NewLineStandardizerTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->plugin = new \AnyMark\Plugins\NewLineStandardizer\NewLineStandardizer();
	}

	/**
	 * @test
	 */
	public function allLineEndingsShouldBeUnixStandard()
	{
		$this->assertEquals(
			"para\nline\nother", $this->plugin->replace("para\rline\r\nother")
		);
	}
}