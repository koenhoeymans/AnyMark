<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Pattern_Patterns_ListItemTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->list = new \AnyMark\Pattern\Patterns\ListItem();
	}

	public function getPattern()
	{
		return $this->list;
	}

	public function createLi($text = null)
	{
		$li = new \AnyMark\ElementTree\Element('li');
		if ($text)
		{
			$text = new \AnyMark\ElementTree\Text($text);
			$li->append($text);
		}

		return $li;
	}

	/**
	 * @test
	 */
	public function listItemsArePrecededByAnAsterisk()
	{
		$text = "\n * an item\n * other item\n";
		$this->assertEquals($this->createLi('an item'), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function listItemsCanAlsoBePrecededByPlusSign()
	{
		$text = "\n + an item\n + other item\n";
		$this->assertEquals($this->createLi('an item'), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function listItemsCanBePrecededByMinusSign()
	{
		$text = "\n - an item\n - other item\n";
		$this->assertEquals($this->createLi('an item'), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function listItemsCanBePrecededWithNumbersFollowedByDot()
	{
		$text = "\n 1. an item\n 2. other item\n";
		$this->assertEquals($this->createLi('an item'), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function listItemsCanBePrecededWithHashFollowedByDot()
	{
		$text = "\n #. an item\n #. other item\n";
		$this->assertEquals($this->createLi('an item'), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canBeUnindented()
	{
		$text = "\n* an item\n* other item\n";
		$this->assertEquals($this->createLi('an item'), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function listItemsCanContinueUnindentedOnFollowingLine()
	{
		$text = "\n * an item\nitem continues\n * other item\n";
		$this->assertEquals($this->createLi("an item\nitem continues"), $this->applyPattern($text));
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

		$this->assertEquals($this->createLi("an item\nitem continues"), $this->applyPattern($text));
 	}

	/**
	 * @test
	 */
	public function itemsCanContainWhiteLines()
	{
		$text =
"
 * an item

   item continues
 * other item
";

		$this->assertEquals($this->createLi("an item\n\nitem continues"), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function afterWhiteLineItemMustBeIndentedOnFirstLine()
	{
		$text =
"
 * an item

item doesnt continue

";
		# note: within a list there wouldn't be two blank lines
		$this->assertEquals($this->createLi("an item\n\n"), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function aListItemCanContainAsterisks()
	{
		$text = "\n * an *item*\n * other item\n";
		$this->assertEquals($this->createLi('an *item*'), $this->applyPattern($text));
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
		$this->assertEquals($this->createLi("an item\n\n"), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function listItemsDoNotNeedBlankLineWhenIndented()
	{
		$text =
'para
  * item
    * subitem
    * subitem
  * item
para';

		$this->assertEquals($this->createLi("item
* subitem
* subitem"), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function listItemsCanBeEmpty()
	{
		$text =
"
 *
 * an item

";

		$this->assertEquals($this->createLi(), $this->applyPattern($text));
	}
}