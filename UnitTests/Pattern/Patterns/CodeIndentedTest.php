<?php

use AnyMark\ElementTree\Element;

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Pattern_Patterns_CodeIndentedTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->pattern = new \AnyMark\Pattern\Patterns\CodeIndented();
	}

	public function getPattern()
	{
		return $this->pattern;
	}

	public function createFromText($text)
	{
		$pre = new \AnyMark\ElementTree\Element('pre');
		$code = new \AnyMark\ElementTree\Element('code');
		$text = new \AnyMark\ElementTree\Text($text);
		$pre->append($code);
		$code->append($text);

		return $pre;
	}

	/**
	 * @test
	 */
	public function indentedTextIsAlsoCode()
	{
		$text =
"paragraph

	code

paragraph";

		$this->assertEquals($this->createFromText('code'), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function variableIndentationIsPossibleWithinCode()
	{
		$text =
"paragraph

		a
	b
		c

paragraph";
		
		$codeText =
"	a
b
	c";

		$this->assertEquals(
			$this->createFromText($codeText), $this->applyPattern($text)
		);
	}

	/**
	 * @test
	 */
	public function onlyBlankLinesBeforeAndAfterInStringAreSufficient()
	{
		$text =
"

	code

";

		$this->assertEquals($this->createFromText('code'), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function codeCanContainBlankLines()
	{
		$text =
"paragraph

	code

	continued

paragraph";

		$this->assertEquals(
			$this->createFromText("code\n\ncontinued"), $this->applyPattern($text)
		);
	}
}