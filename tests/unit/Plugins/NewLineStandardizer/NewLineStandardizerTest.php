<?php

namespace AnyMark\Plugins\NewLineStandardizer;

class NewLineStandardizerTest extends \PHPUnit_Framework_TestCase
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