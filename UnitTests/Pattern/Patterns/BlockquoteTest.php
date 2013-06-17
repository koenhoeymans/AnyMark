<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Pattern_Patterns_BlockquoteTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->pattern = new \AnyMark\Pattern\Patterns\Blockquote();
	}

	protected function getPattern()
	{
		return $this->pattern;
	}

	/**
	 * @test
	 */
	public function blockquotesArePrecededByGreaterThanSignsOnEveryLine()
	{
		$text =
"paragraph

> quote
> continued

paragraph";

		$bq = $this->elementTree()->createElement('blockquote');
		$bq->append(new \ElementTree\ElementTreeText("quote\ncontinued\n\n"));

		$this->assertEquals($bq, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function greaterThanSignIsOnlyNecessaryOnFirstLine()
	{
		$text =
"paragraph

> quote
continued

paragraph";

		$bq = $this->elementTree()->createElement('blockquote');
		$bq->append(new \ElementTree\ElementTreeText("quote\ncontinued\n\n"));

		$this->assertEquals($bq, $this->applyPattern($text));
		
	}

	/**
	 * @test
	 */
	public function canContainABlockquote()
	{
		$text =
"paragraph

> quote
>
> > subquote
>
> quote continued

paragraph";

		$bq = $this->elementTree()->createElement('blockquote');
		$bq->append(new \ElementTree\ElementTreeText("quote\n\n> subquote\n\nquote continued\n\n"));

		$this->assertEquals($bq, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canBeDirectlyAfterParagraph()
	{
		$text =
"paragraph
> quote
> continued

paragraph";

		$bq = $this->elementTree()->createElement('blockquote');
		$bq->append(new \ElementTree\ElementTreeText("quote\ncontinued\n\n"));

		$this->assertEquals($bq, $this->applyPattern($text));
	}
}