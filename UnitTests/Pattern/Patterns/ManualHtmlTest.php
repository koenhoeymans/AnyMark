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
	public function grabsCodeTagsToPutIntoDom()
	{
		$text = "foo <a>b</a> bar";

		$el = new \DOMElement('a', 'b');

		$this->assertCreatesDomFromText($el, $text);
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

		$el = new \DOMComment(' comment ');
		$this->assertCreatesDomFromText($el, $text);
	}

	/**
	 * @test
	 */
	public function canContainOtherHtmlTags()
	{
		$text = "foo <a><b>c</b></a> bar";

		$el = new \DOMElement('a', '<b>c</b>');

		$this->assertCreatesDomFromText($el, $text);
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
	
		$el = new \DOMElement('div', "<div><div>
foo
</div><div style=\">\"/></div><div>bar</div>");

		$this->assertCreatesDomFromText($el, $text);
	}

	/**
	 * @test
	 */
	public function handlesRecursion()
	{
		$text = "<a><a><a>b</a></a></a>";

		$el = new \DOMElement('a', '<a><a>b</a></a>');

		$this->assertCreatesDomFromText($el, $text);
	}

	/**
  	 * @test
	 */
	public function replacesSelfClosingElements()
	{
		$text = "<hr />";

		$el = new \DOMElement('hr');

		$this->assertCreatesDomFromText($el, $text);
	}

	/**
	 * @test
	 */
	public function htmlCanContainSelfClosingTag()
	{
		$text = "<div><div a=\"b\"/></div></div>";

		$el = new \DOMElement('div', '<div a="b"/>');

		$this->assertCreatesDomFromText($el, $text);
	}

	/**
	 * @test
	 */
	public function canContainMultipleAttributes()
	{
		$text = "<a id='b' class='c'>d</a>";
	
		$dom = new \DOMDocument();
		$el = new \DOMElement('a', 'd');
		$dom->appendChild($el);
		$el->setAttribute('id', 'b');
		$el->setAttribute('class', 'c');

		$this->assertCreatesDomFromText($el, $text);
	}

	/**
	 * @test
	 */
	public function attributeValuesCanContainBackticks()
	{
		$text = "<a class='`ticks`'>b</a>";

		$dom = new \DOMDocument();
		$el = new \DOMElement('a', 'b');
		$dom->appendChild($el);
		$el->setAttribute('class', '`ticks`');

		$this->assertCreatesDomFromText($el, $text);
	}

	/**
	 * @test
	 */
	public function ifBlankLineBeforeAndAfterTagsAndTextOnSameLineAsTagItIsAParagraph()
	{
		$text = "\n\n<span>a paragraph</span>\n\n";
		$this->assertDoesNotCreateDomFromText($text);
	}

	/**
	 * @test
	 */
	public function ifStartOfTextAndAfterTagsBlankLineItIsAParagraph()
	{
		$text = "<span>a paragraph</span>\n\n";
		$this->assertDoesNotCreateDomFromText($text);
	}

	/**
	 * @test
	 */
	public function ifNoBlankLineBeforeAndAfterItIsNoParagraph()
	{
		$text = "text <span>not a paragraph</span> text";

		$el = new \DOMElement('span', 'not a paragraph');

		$this->assertCreatesDomFromText($el, $text);
	}

	/**
	 * @test
	 * the newlines before and after the tag made this not work
	 */
	public function indentedTextBetweenTags()
	{
		$text = "\n<div>\n\tinside\n</div>\n";
		
		$el = new \DOMElement('div', "\n\tinside\n");
		
		$this->assertCreatesDomFromText($el, $text);
	}
}