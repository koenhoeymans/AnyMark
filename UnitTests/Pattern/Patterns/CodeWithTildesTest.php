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
	public function codeCanBeSurroundedByTwoLinesOfAtLeastThreeTildes()
	{
		$text = "\n\n~~~\nthe code\n~~~\n\n";

		$this->assertEquals(
			$this->createFromText('the code'), $this->applyPattern($text)
		);
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

		$this->assertEquals($this->createFromText($codeText), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function firstCharacterDeterminesIndentation()
	{
		$text = "\n\n~~~\n\tindented\n\t\tdoubleindented\n~~~\n\n";

		$this->assertEquals(
			$this->createFromText("indented\n\tdoubleindented"),
			$this->applyPattern($text)
		);
	}

	/**
	 * @test
	 */
	public function wholeTildeCodeBlockCanBeIndented()
	{
		$text = "\n\n\t~~~\n\tthe code\n\t~~~\n\n";

		$this->assertEquals(
			$this->createFromText('the code'), $this->applyPattern($text)
		);
	}

	/**
	 * @test
	 */
	public function tildeCodeBlockIsNonGreedy()
	{
		$text = "\n\n~~~\nthe code\n~~~\n\nparagraph\n\n~~~\ncode\n~~~\n\n";

		$this->assertEquals(
			$this->createFromText('the code'), $this->applyPattern($text)
		);
	}

	/**
	 * @test
	 */
	public function canHaveOwnClassSpecified()
	{
		$text = "\n\n~~~{.language-foo .example}\nthe code\n~~~\n\n";
		$code = $this->createFromText('the code');
		$code->setAttribute('class', 'language-foo example');

		$this->assertEquals($code, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canHaveIdSpecified()
	{
		$text = "\n\n~~~{#example}\nthe code\n~~~\n\n";
		$code = $this->createFromText('the code');
		$code->setAttribute('id', 'example');

		$this->assertEquals($code, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canHaveAttributeSpecified()
	{
		$text = "\n\n~~~{foo=\"bar\"}\nthe code\n~~~\n\n";
		$code = $this->createFromText('the code');
		$code->setAttribute('foo', 'bar');

		$this->assertEquals($code, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canHaveCombinationsOfMultipleClassIdAttributeSpecified()
	{
		$text = "\n\n~~~{foo=\"bar\" .myClass #myId .otherClass #otherId}\nthe code\n~~~\n\n";
		$code = $this->createFromText('the code');
		$code->setAttribute('foo', 'bar');
		$code->setAttribute('id', 'myId otherId');
		$code->setAttribute('class', 'myClass otherClass');
		
		$this->assertEquals($code, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canHaveDuplicateAttributesWithDifferentValues()
	{
		$text = "\n\n~~~{foo=\"bar\" foo=\"baz\"}\nthe code\n~~~\n\n";
		$code = $this->createFromText('the code');
		$code->setAttribute('foo', 'bar baz');
		
		$this->assertEquals($code, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function shortCutToDeclaringLanguage()
	{
		$text = "\n\n~~~ html\nthe code\n~~~\n\n";
		$code = $this->createFromText('the code');
		$code->setAttribute('class', 'language-html');
		
		$this->assertEquals($code, $this->applyPattern($text));
	}
}