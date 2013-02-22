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

	public function createList($type, $content)
	{
		$list = $this->elementTree()->createElement($type);
		$list->append($this->elementTree()->createText($content));

		return $list;
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

		$this->assertEquals(null, $this->applyPattern($text));
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
		$list = $this->createList('ul', "* an item\n* other item");

		$this->assertEquals($list, $this->applyPattern($text));
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
		$list = $this->createList('ul', "* an item\n* other item");

		$this->assertEquals($list, $this->applyPattern($text));
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
		$list = $this->createList('ul', "* an item\n* other item");

		$this->assertEquals($list, $this->applyPattern($text));
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

		$this->assertEquals(null, $this->applyPattern($text));
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
		$list = $this->createList('ul', "* an item\n* other item");

		$this->assertEquals($list, $this->applyPattern($text));	
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

		$this->assertEquals(null, $this->applyPattern($text));
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
		$list = $this->createList('ul', "* an item\n* other item");

		$this->assertEquals($list, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canBeEndOfFile()
	{
		$text = "

 * an item
 * other item";
		$list = $this->createList('ul', "* an item\n* other item");

		$this->assertEquals($list, $this->applyPattern($text));
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
		$list = $this->createList('ul', "* an item\n\n  item continues\n\n* other item");

		$this->assertEquals($list, $this->applyPattern($text));
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
		$list = $this->createList('ul', "* an item");

		$this->assertEquals($list, $this->applyPattern($text));
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
		$list = $this->createList('ol', "1. an item\n2. other item");

		$this->assertEquals($list, $this->applyPattern($text));
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
		$list = $this->createList('ol', "#. an item\n#. other item");

		$this->assertEquals($list, $this->applyPattern($text));
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
		$list = $this->createList('ol', "15. an item\n52. other item");

		$this->assertEquals($list, $this->applyPattern($text));
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
		$list = $this->createList('ul', "*	an item

		code

	item continued");

		$this->assertEquals($list, $this->applyPattern($text));
	}
}