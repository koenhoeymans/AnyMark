<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Pattern_Patterns_ManualHtmlTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->pattern = new \AnyMark\Pattern\Patterns\ManualHtml();
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
		$text = "foo <a>b</a> bar";
		$el = $this->create('a', 'b');

		$this->assertEquals($el, $this->applyPattern($text));
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
		$text = "foo <a><b>c</b></a> bar";
		$el = $this->create('a', '<b>c</b>');

		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canContainOtherHtmlTags_2()
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
	public function htmlCanContainSelfClosingTag()
	{
		$text = "<div><div a=\"b\"/></div></div>";

		$el = $this->create('div', '<div a="b"/>');

		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canContainMultipleAttributes()
	{
		$text = "<a id='b' class='c'>d</a>";
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
		$text = "<a class='`ticks`'>b</a>";
		$el = $this->create('a', 'b');
		$el->setAttribute('class', '`ticks`');

		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function ifBlankLineBeforeAndAfterTagsAndTextOnSameLineAsTagItIsAParagraph()
	{
		$text = "\n\n<span>a paragraph</span>\n\n";
		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function ifStartOfTextAndAfterTagsBlankLineItIsAParagraph()
	{
		$text = "<span>a paragraph</span>\n\n";
		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function ifNoBlankLineBeforeAndAfterItIsNoParagraph()
	{
		$text = "text <span>not a paragraph</span> text";
		$el = $this->create('span', 'not a paragraph');

		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
	 * @test
	 * the newlines before and after the tag made this not work
	 */
	public function indentedTextBetweenTags()
	{
		$text = "\n<div>\n\tinside\n</div>\n";
		$el = $this->create('div', "\n\tinside\n");
		
		$this->assertEquals($el, $this->applyPattern($text));
	}
}