<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Pattern_Patterns_CodeWithTildesTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->pattern = new \AnyMark\Pattern\Patterns\CodeWithTildes();
	}

	public function getPattern()
	{
		return $this->pattern;
	}

	public function createDomFromText($text)
	{
		$domDoc = new \DOMDocument();
		$domElementCode = new \DOMElement('code', $text);
		$domElementPre = new \DOMElement('pre');
		$domDoc->appendChild($domElementPre);
		$domElementPre->appendChild($domElementCode);
		return $domElementPre;
	}

	/**
	 * @test
	 */
	public function codeCanBeSurroundedByTwoLinesOfAtLeastThreeTildes()
	{
		$text = "\n\n~~~\nthe code\n~~~\n\n";
		$this->assertCreatesDomFromText($this->createDomFromText('the code'), $text);
	}

	/**
	 * @test
	 */
	public function tildeBlockCanContainRowOfTildesIfTheyAreIndented()
	{
		$text = "

~~~
	example

	~~~

	code

	~~~

~~~

";

		$codeText =
"example

~~~

code

~~~";

		$this->assertCreatesDomFromText($this->createDomFromText($codeText), $text);
	}

	/**
	 * @test
	 */
	public function afterThreeTildesCanBeAnyText()
	{
		$text = "\n\n~~~ code ~~~\nthe code\n~~~~~~~~\n\n";
		$this->assertCreatesDomFromText($this->createDomFromText('the code'), $text);
	}

	/**
	 * @test
	 */
	public function firstCharacterDeterminesIndentation()
	{
		$text = "\n\n~~~\n\tindented\n\t\tdoubleindented\n~~~\n\n";
		$this->assertCreatesDomFromText(
			$this->createDomFromText("indented\n\tdoubleindented"), $text
		);
	}

	/**
	 * @test
	 */
	public function wholeTildeCodeBlockCanBeIndented()
	{
		$text = "\n\n\t~~~\n\tthe code\n\t~~~\n\n";
		$this->assertCreatesDomFromText($this->createDomFromText('the code'), $text);
	}

	/**
	 * @test
	 */
	public function tildeCodeBlockIsNonGreedy()
	{
		$text = "\n\n~~~\nthe code\n~~~\n\nparagraph\n\n~~~\ncode\n~~~\n\n";
		$this->assertCreatesDomFromText($this->createDomFromText('the code'), $text);
	}
}