<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Plugins_EmptyLineFixer_EmptyLineFixerTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->plugin = new \AnyMark\Plugins\EmptyLineFixer\EmptyLineFixer();
	}

	/**
	 * @test
	 */
	public function lineWithOnlySpacesAndOrTabsIsCleaned()
	{
		$this->assertEquals(
			"para\n\npara", $this->plugin->fix("para\n \t\npara")
		);
	}
}