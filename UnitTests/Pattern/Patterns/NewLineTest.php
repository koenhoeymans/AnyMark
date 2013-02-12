<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Pattern_Patterns_NewLineTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->pattern = new \AnyMark\Pattern\Patterns\NewLine();
	}

	protected function getPattern()
	{
		return $this->pattern;
	}

	/**
	 * @test
	 */
	public function doubleSpaceAtEndOfLineBecomesNewLine()
	{
		$text = "Some text before  \nand after double space";
		$br = new \AnyMark\ComponentTree\Element('br');
		$this->assertEquals($br, $this->applyPattern($text));
	}
}