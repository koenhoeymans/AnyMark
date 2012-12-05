<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Pattern_Patterns_TextualListTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->list = new \AnyMark\Pattern\Patterns\TextualList();
	}

	protected function getPattern()
	{
		return $this->list;
	}

	/**
	 * @test
	 */
	public function blankLineNecessaryBefore()
	{
		$text =
"paragraph
* an item
* other item

paragraph";

		$this->assertDoesNotCreateDomFromText($text);
	}

	/**
	 * @test
	 */
	public function noBlankLineBeforeNecessaryWhenIndented()
	{
		$text =
"paragraph
 * an item
 * other item

paragraph";
		$dom = new \DOMElement('ul', "* an item\n* other item");
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function canBeUnindentedAfterBlankLine()
	{
		$text =
"

* an item
* other item

";
		$dom = new \DOMElement('ul', "* an item\n* other item");
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function canBeIndented()
	{
		$text =
"

 * an item
 * other item

";

		$dom = new \DOMElement('ul', "* an item\n* other item");
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 * Note: Another list item would trigger a list, but in end
	 * to end situations code would have triggered a match first.
	 */
	public function noListWhenBlankLineAndTabIndented()
	{
		$text =
"paragraph

	* an item";

		$this->assertDoesNotCreateDomFromText($text);
	}

	/**
	 * @test
	 */
	public function listWhenTabIndentedAfterParagraphWithoutBlankLine()
	{
		$text =
"paragraph
	* an item
	* other item

paragraph
";

		$dom = new \DOMElement('ul', "* an item\n* other item");
		$this->assertCreatesDomFromText($dom, $text);		
	}

	/**
	 * @test
	 * Note: See note above.
	 */
	public function noListWhenMoreThanThreeSpacesIndented()
	{
		$text =
"paragraph

    * an item";

		$this->assertDoesNotCreateDomFromText($text);
	}

	/**
	 * @test
	 */
	public function canBeStartOfFile()
	{
		$text =
" * an item
 * other item

";
		$dom = new \DOMElement('ul', "* an item\n* other item");
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function canBeEndOfFile()
	{
		$text = "

 * an item
 * other item";
		$dom = new \DOMElement('ul', "* an item\n* other item");
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function listsCanContainBlankLines()
	{
		$text =
"

 * an item

   item continues

 * other item

";

		$dom = new \DOMElement('ul', "* an item\n\n  item continues\n\n* other item");
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function afterBlankLineItemMustBeIndentedOnFirstLine()
	{
		$text = "
 * an item

item continues ...  not

";
		$dom = new \DOMElement('ul', "* an item");
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function orderedListsAreCreatedByNumberFollowedByDotAsListMarker()
	{
		$text =
"not a paragraph

1. an item
2. other item

paragraph";
		$dom = new \DOMElement('ol', "1. an item\n2. other item");
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function orderedListsCanAlsoBeCreatedByHashSignFollowedByDot()
	{
		$text =
"a paragraph

#. an item
#. other item

paragraph";
		$dom = new \DOMElement('ol', "#. an item\n#. other item");
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function actualNumberDoesNotNeedToBeOneTwoThreeEtc()
	{
		$text =
"paragraph

15. an item
52. other item

paragraph";
		$dom = new \DOMElement('ol', "15. an item\n52. other item");
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function codeBlockInList()
	{
		$text =
"paragraph

*	an item

		code

	item continued

paragraph";
		$dom = new \DOMElement('ul', "*	an item

		code

	item continued");
		$this->assertCreatesDomFromText($dom, $text);
	}
}