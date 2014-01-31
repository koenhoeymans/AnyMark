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

	public function create($tag, $text = null)
	{
		$element = $this->elementTree()->createElement($tag);
		if ($text)
		{
			$element->append($this->elementTree()->createText($text));
		}

		return $element;
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
	public function alsoBlankLineBeforeNecessaryWhenIndentedLessThanFourSpacesAfterParagraph()
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
	public function canBeUnindentedAfterBlankLine()
	{
		$text =
"

* an item
* other item

";
		$list = $this->create('ul');
		$list->append($this->create('li', 'an item'));
		$list->append($this->create('li', 'other item'));

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
		$list = $this->create('ul');
		$list->append($this->create('li', 'an item'));
		$list->append($this->create('li', 'other item'));

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

		$this->assertNull($this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function noListWhenMoreThanThreeSpacesIndentedForFirstLevel()
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
		$list = $this->create('ul');
		$list->append($this->create('li', 'an item'));
		$list->append($this->create('li', 'other item'));

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
		$list = $this->create('ul');
		$list->append($this->create('li', 'an item'));
		$list->append($this->create('li', 'other item'));

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
		$list = $this->create('ul');
		$list->append($this->create('li', "\n\nan item\n\nitem continues\n\n"));
		$list->append($this->create('li', "\n\nother item\n\n"));
	
		$this->assertEquals($list, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function listItemsCanBeSeperatedByABlankLine()
	{
		$text =
"
 * an item

 * other item

";
		$list = $this->create('ul');
		$list->append($this->create('li', "\n\nan item\n\n"));
		$list->append($this->create('li', "\n\nother item\n\n"));

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
		$list = $this->create('ul');
		$list->append($this->create('li', 'an item'));

		$this->assertEquals($list, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function listItemsCanContinueUnindentedOnFollowingLine()
	{
		$text = "
 * an item
item continues
 * other item
";
		$list = $this->create('ul');
		$list->append($this->create('li', "an item\nitem continues"));
		$list->append($this->create('li', "other item"));

		$this->assertEquals($list, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function listItemsCanContinueIndentedOnFollowingLine()
	{
		$text =
"
 * an item
   item continues
 * other item
";
		$list = $this->create('ul');
		$list->append($this->create('li', "an item\nitem continues"));
		$list->append($this->create('li', "other item"));

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
		$list = $this->create('ol');
		$list->append($this->create('li', 'an item'));
		$list->append($this->create('li', 'other item'));
	
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
		$list = $this->create('ol');
		$list->append($this->create('li', 'an item'));
		$list->append($this->create('li', 'other item'));

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
		$list = $this->create('ul');
		$list->append($this->create('li', "\tan item

		code

	item continued"));
		
		$this->assertEquals($list, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function listItemsCanAlsoBePrecededByPlusSign()
	{
		$text = "\n + an item\n + other item\n";
		$list = $this->create('ul');
		$list->append($this->create('li', 'an item'));
		$list->append($this->create('li', 'other item'));

		$this->assertEquals($list, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function listItemsCanBePrecededByMinusSign()
	{
		$text = "\n - an item\n - other item\n";
		$list = $this->create('ul');
		$list->append($this->create('li', 'an item'));
		$list->append($this->create('li', 'other item'));

		$this->assertEquals($list, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function listItemsCanBePrecededWithNumbersFollowedByDot()
	{
		$text = "\n 1. an item\n 2. other item\n";
		$list = $this->create('ol');
		$list->append($this->create('li', 'an item'));
		$list->append($this->create('li', 'other item'));

		$this->assertEquals($list, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function aListItemCanContainAsterisks()
	{
		$text = "\n * an *item*\n * other item\n";
		$list = $this->create('ul');
		$list->append($this->create('li', 'an *item*'));
		$list->append($this->create('li', 'other item'));

		$this->assertEquals($list, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function listItemsCanBeEmpty()
	{
		$text =
"
 * item
 *

";

		$list = $this->create('ul');
		$list->append($this->create('li', 'item'));
		$list->append($this->create('li'));

		$this->assertEquals($list, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function recognizesWhenUnorderedListsIsFollowedByOrdered()
	{
		$text = "
* an item
* other item

1. item in second list
2. other item in second list
";
		$list = $this->create('ul');
		$list->append($this->create('li', 'an item'));
		$list->append($this->create('li', 'other item'));

		$this->assertEquals($list, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function recognizesWhenUnorderedListsIsFollowedByOrderedWhenAdjacent()
	{
		$text = "
* an item
* other item
1. item in second list
2. other item in second list
";
		$list = $this->create('ul');
		$list->append($this->create('li', 'an item'));
		$list->append($this->create('li', 'other item'));

		$this->assertEquals($list, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function recognizesWhenOrderedListsIsFollowedByUnOrderedWhenAdjacent()
	{
		$text = "
1. item
2. other item
* an item
* another item
";
		$list = $this->create('ol');
		$list->append($this->create('li', 'item'));
		$list->append($this->create('li', 'other item'));

		$this->assertEquals($list, $this->applyPattern($text));
	}
}