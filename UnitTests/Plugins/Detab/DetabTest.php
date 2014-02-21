<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Plugins_Detab_DetabTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->plugin = new \AnyMark\Plugins\Detab\Detab();
	}

	/**
	 * @test
	 */
	public function replacesTabBySpaces()
	{
		$this->assertEquals("para\n    \npara", $this->plugin->detab("para\n\t\npara"));
	}
}