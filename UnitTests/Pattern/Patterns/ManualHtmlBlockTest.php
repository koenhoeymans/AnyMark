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
	public function grabsCodeTagsToPutIntoComponent()
	{
		$text = "foo\n<a>b</a>\nbar";
		$el = $this->create('a', 'b');

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
		$text = "foo\n<a><b>c</b></a>\nbar";
		$el = $this->create('a', '<b>c</b>');

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
		$text = "foo\n<a>\n\t<b>c</b>\n</a>\nbar";
		$el = $this->create('a', "\n<b>c</b>\n");

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
		$el = $this->create('div', "\n<div>\n\t<div>\n\t\tfoo\n\t</div>\n</div>\n");

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
		$el = $this->create('div', "\ncode block\n\n\ta code </div>\n\nparagraph\n");

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
		$el = $this->create('div', "\ncode block\n\n\t</div>\n\nparagraph\n");

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
		$el = $this->create('div', "\n<div>\n\tfoo\n</div>\n");

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
		$div->append($div->createText("\nwith `<foo>`\n"));

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
		$text = "<a><a><a>b</a></a></a>";
		$el = $this->create('a', '<a><a>b</a></a>');

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
		$text = "<a id=\"b\" class=\"c\">d</a>";
		$el = $this->create('a', 'd');
		$el->setAttribute('id', 'b');
		$el->setAttribute('class', 'c');

		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function attributeValuesCanContainBackticks()
	{
		$text = "<a class=\"`ticks`\">b</a>";
		$el = $this->create('a', 'b');
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
		$text = "foo\n<a>b</a>\t \nbar";
		$el = $this->create('a', 'b');

		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function whitespaceAfterIsAllowedForComments()
	{
		$text =
"paragraph

<!-- comment --> \t

paragraph";
		$el = $this->elementTree()->createComment(' comment ');

		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function ifFullParagraphItLeavesUntouchedForParagraph()
	{
		$text = "foo\n\n<div>bar</div>\n\nbar";

		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function notParagraphWhenStartTagOnOwnLine()
	{
		$text = "

<del>
<p>Some text</p>
</del>

";
		$el = $this->create('del', "\n<p>Some text</p>\n");

		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function twoElementsOnFollowingLineIsParagraphFirst()
	{
		$text = "

<abbr>SB</abbr>
<abbr>SB</abbr>

";

		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function startOfTextOneLineIsParagraphFirst()
	{
		$text = "<abbr>SB</abbr>

paragraph

";

		$this->assertEquals(null, $this->applyPattern($text));		
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
		$text = "<a id='b' class=\"c\">d</a>";

		$this->assertEquals(
			"<a id='b' class=\"c\">d</a>", $this->applyPattern($text)->toString()
		);
	}
}