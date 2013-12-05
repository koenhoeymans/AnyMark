<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Pattern_Patterns_ManualHtmlBlockTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->pattern = new \AnyMark\Pattern\Patterns\ManualHtmlBlock();
	}

	public function getPattern()
	{
		return $this->pattern;
	}

	public function create($tag, $content)
	{
		$element = $this->elementTree()->createElement($tag);
		$text = new \ElementTree\ElementTreeText($content);
		$element->append($text);

		return $element;
	}

	/**
	 * @test
	 */
	public function ifInlineItIsParagrapParagraph()
	{
		$text = "foo

<b>bar</b>

bar";

		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function ifOnOneLineItDoesntAddNewlines()
	{
		$text = "foo

<div>bar</div>

bar";

		$el = $this->create('div', 'bar');

		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function doesntGrabHtmlWithTagsBetweenText()
	{
		$text = "foo <a>b</a> bar";
		$this->assertNull($this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function grabsHtmlComments()
	{
		$text =
"paragraph

<!-- comment -->

paragraph";
		$el = $this->elementTree()->createComment(' comment ');

		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canContainOtherHtmlTags()
	{
		$text = "foo
<div><b>c</b></div>
bar";

		$el = $this->create('div', '<b>c</b>');

		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canContainOtherHtmlTagsWithContentOnDifferentLine()
	{
		$text =
"<div><div><div>
foo
</div><div style=\">\"/></div><div>bar</div></div>";
		$el = $this->create('div', "<div><div>
foo
</div><div style=\">\"/></div><div>bar</div>");

		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canContainOtherHtmlTagsOnDifferentLinesWhichWillBeUnindented()
	{
		$text = "foo
<div>
	<b>c</b>
</div>
bar";
		$el = $this->create('div', "\n\t<b>c</b>\n");

		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canContainOtherHtmlTagsWithMixedManualHtmlAndTagsInCodeBlocks()
	{
		$text = "

<div>
	<div>

	Code block:

		</div>

	Code span: `</div>`.

	</div>
</div>

";
		$el = $this->create('div', "
	<div>

	Code block:

		</div>

	Code span: `</div>`.

	</div>
");

		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function doenstUnindentsSpaces()
	{
		$text = "

<div>
    foo
</div>

";
		$el = $this->create('div', "\n    foo\n");

		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canContainOtherHtmlTagsOnDifferentLinesWhichWillBeUnindented_2()
	{
		$text = "
<div>
	<div>
		<div>
			foo
		</div>
	</div>
</div>
";
		$el = $this->create('div', "\n\t<div>\n\t\t<div>\n\t\t\tfoo\n\t\t</div>\n\t</div>\n");

		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canContainOtherHtmlTagsInCodeBlockWithPrecedingText()
	{
		$text = "
<div>
	code block

		a code </div>

	paragraph
</div>

";
		$el = $this->create('div', "\n\tcode block\n\n\t\ta code </div>\n\n\tparagraph\n");

		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canContainOtherHtmlTagsInCodeBlockWithoutPrecedingText()
	{
		$text = "
<div>
	code block

		</div>

	paragraph
</div>

";
		$el = $this->create('div', "\n\tcode block\n\n\t\t</div>\n\n\tparagraph\n");

		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canContainOtherHtmlTagsOnDifferentLinesWhichWillBeUnindented_3()
	{
		$text = "

<div>
	<div>
		foo
	</div>
</div>

";
		$el = $this->create('div', "\n\t<div>\n\t\tfoo\n\t</div>\n");

		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canContainContentWithTagInCodeBlock()
	{
		$text = "

<div>
	with `<foo>`
</div>

";
		$div = $this->elementTree()->createElement('div');
		$div->append($div->createText("\n\twith `<foo>`\n"));

		$this->assertEquals($div, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function noUnindentWhenSomeTextNotIndented()
	{
		$text = "

<div>
    foo

bar

    foo
</div>

";
		$div = $this->elementTree()->createElement('div');
		$div->append($div->createText("\n    foo\n\nbar\n\n    foo\n"));

		$this->assertEquals($div, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function handlesRecursion()
	{
		$text = "<div><div><div>b</div></div></div>";
		$el = $this->create('div', '<div><div>b</div></div>');

		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
  	 * @test
	 */
	public function replacesSelfClosingElements()
	{
		$text = "<hr />";
		$el = $this->elementTree()->createElement('hr');

		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function htmlCanBeEmptyElement()
	{
		$text = "<div></div>";
		$el = $this->elementTree()->createElement('div');

		$this->assertEquals($el, $this->applyPattern($text));		
	}

	/**
	 * @test
	 */
	public function htmlCanContainEmptyElement()
	{
		$text = "<div><div a=\"b\"></div></div>";

		$el = $this->create('div', '<div a="b"></div>');

		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canContainMultipleAttributes()
	{
		$text = "<div id=\"b\" class=\"c\">d</div>";
		$el = $this->create('div', 'd');
		$el->setAttribute('id', 'b');
		$el->setAttribute('class', 'c');

		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function attributeValuesCanContainBackticks()
	{
		$text = "<div class=\"`ticks`\">b</div>";
		$el = $this->create('div', 'b');
		$el->setAttribute('class', '`ticks`');

		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function attributeCanStartOnNextLine()
	{
		$text = "<p class=\"test\"
id=\"12\">
content
</p>";
		$el = $this->create('p', "\ncontent\n");
		$el->setAttribute('class', 'test');
		$el->setAttribute('id', '12');

		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function whitespaceAfterIsAllowedForElements()
	{
		$text = "foo
<div>b</div> \t
bar";
		$el = $this->create('div', 'b');

		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function whitespaceAfterIsAllowedForComments()
	{
		$text =
"paragraph

<!-- comment -->   

paragraph";
		$el = $this->elementTree()->createComment(' comment ');

		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function notParagraphWhenStartTagOnOwnLine()
	{
		$text = "

<div>
<p>Some text</p>
</div>

";
		$el = $this->create('div', "\n<p>Some text</p>\n");

		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function selfclosingCanContainNewline()
	{
		$text = "

<hr class=\"foo\"
    id=\"bar\" >

	";

		$el = $this->elementTree()->createElement('hr');
		$el->setAttribute('class', 'foo');
		$el->setAttribute('id', 'bar');
		
		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function keepsQuoteStyle()
	{
		$text = "<div id='b' class=\"c\">d</div>";

		$this->assertEquals(
			"<div id='b' class=\"c\">d</div>", $this->applyPattern($text)->toString()
		);
	}

	/**
	 * @test
	 */
	public function tagsOnOwnLineCannotHaveLastTagIndentedAsCodeBlock()
	{
		$text = "

<foo>

Code block:

    </foo>

para";

		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function insAndDelCanBeInline()
	{
		$text = "para
		
<ins>inline</ins>

para";

		$this->assertEquals(null, $this->applyPattern($text));		
	}

	/**
	 * @test
	 */
	public function insAndDelCanBeBlock()
	{
		$text = "para

<ins>
block
</ins>

para";

		$el = $this->create('ins', "\nblock\n");

		$this->assertEquals($el, $this->applyPattern($text));
	}
}