<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Pattern_Patterns_ManualHtmlInlineTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->pattern = new \AnyMark\Pattern\Patterns\ManualHtmlInline();
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
	public function grabsCodeTagsPlacedInlineToPutIntoComponent()
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
		$text = "paragraph <!-- comment --> paragraph";
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
"foo <div><div><div>
foo
</div><div style=\">\"/></div><div>bar</div></div> bar";
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
		$text = "foo <a><a><a>b</a></a></a> bar";
		$el = $this->create('a', '<a><a>b</a></a>');

		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
  	 * @test
	 */
	public function replacesSelfClosingElements()
	{
		$text = "foo <div /> bar";
		$el = $this->elementTree()->createElement('div');

		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function htmlCanContainSelfClosingTag()
	{
		$text = "foo <div><div a=\"b\"/></div></div> bar";

		$el = $this->create('div', '<div a="b"/>');

		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canContainMultipleAttributes()
	{
		$text = "foo <a id='b' class='c'>d</a> bar";
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
		$text = "foo <a class='`ticks`'>b</a> bar";
		$el = $this->create('a', 'b');
		$el->setAttribute('class', '`ticks`');

		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function ifNewlineBeforeAndAfterItIsNotInline()
	{
		$text = "\n<span>a paragraph</span>\n";
		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function ifNewlineBeforeAndTextAfterItIsInline()
	{
		$text = "\n<span>a paragraph</span> foo\n";
		$el = $this->create('span', 'a paragraph');

		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function ifTextBeforeAndNewlineAfterItIsInline()
	{
		$text = "\nfoo <span>a paragraph</span>\n";
		$el = $this->create('span', 'a paragraph');

		$this->assertEquals($el, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function ifStartOfTextAndAfterTagsBlankLineItIsNotInline()
	{
		$text = "<span>a paragraph</span>\n";
		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function ifNewLineBeforeAndEndItIsNotInline()
	{
		$text = "\n<span>a paragraph</span>";
		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function ifStartAndEndItIsInline()
	{
		$text = "<span>foo</span>";
		$el = $this->create('span', 'foo');

		$this->assertEquals($el, $this->applyPattern($text));
	}
}