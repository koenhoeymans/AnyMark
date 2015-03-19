<?php

namespace Anymark;

class AnyMark_Pattern_Patterns_CodeInlineTest extends PatternReplacementAssertions
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
		$code = $this->elementTree()->createElement('code');
		$text = $this->elementTree()->createText($text);
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