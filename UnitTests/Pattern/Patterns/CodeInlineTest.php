<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Pattern_Patterns_CodeInlineTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->codeInline = new \AnyMark\Pattern\Patterns\CodeInline();
	}

	public function getPattern()
	{
		return $this->codeInline;
	}

	public function createFromText($text)
	{
		$code = new \AnyMark\ComponentTree\Element('code');
		$text = new \AnyMark\ComponentTree\Text($text);
		$code->append($text);

		return $code;
	}

	/**
	 * @test
	 */
	public function transformsCodeBetweenBackticks()
	{
		$text = 'Text with `code` in between.';

		$this->assertEquals($this->createFromText('code'), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canStartAndEndWithMultipleBackticks()
	{
		$text = 'Text with ``code`` in between.';

		$this->assertEquals($this->createFromText('code'), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function backtickCanBePlacedWithinMultipleBackticks()
	{
		$text = 'Text with ``co`de`` in between.';

		$this->assertEquals($this->createFromText('co`de'), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function takesTheMostOutwardBackticks()
	{
		$text = 'Text with ``code``` in between.';

		$this->assertEquals($this->createFromText('code`'), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function backslashEscapes()
	{
		$text = 'Code \`that` is escaped.';

		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function backticksCanBeFollowedByNonSpace()
	{
		$text = 'This `code`: look below.';

		$this->assertEquals($this->createFromText('code'), $this->applyPattern($text));
	}
}