<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_ElementTree_ElementTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function elementsHaveName()
	{
		$element = new \AnyMark\ElementTree\Element('a');

		$this->assertEquals('a', $element->getName());
	}

	/**
	 * @test
	 */
	public function elementsHaveAttributes()
	{
		$element = new \AnyMark\ElementTree\Element('a');
		$element->setAttribute('name', 'value');

		$this->assertEquals('value', $element->getAttributeValue('name'));		
	}

	/**
	 * @test
	 */
	public function elementsCanHaveElements()
	{
		$a = new \AnyMark\ElementTree\Element('a');
		$b = new \AnyMark\ElementTree\Element('b');
		$a->append($b);

		$this->assertEquals(array($b), $a->getChildren());
	}

	/**
	 * @test
	 */
	public function elementsCanHaveText()
	{
		$a = new \AnyMark\ElementTree\Element('a');
		$text = new \AnyMark\ElementTree\Text('text');
		$a->append($text);

		$this->assertEquals(array($text), $a->getChildren());
	}

	/**
	 * @test
	 */
	public function isParentOfAppended()
	{
		$parent = new \AnyMark\ElementTree\Element('parent');
		$child = new \AnyMark\ElementTree\Element('child');
		$parent->append($child);

		$this->assertEquals($parent, $child->getParent());
	}

	/**
	 * @test
	 */
	public function canAppendAfterComponent()
	{
		$a = new \AnyMark\ElementTree\Element('a');
		$b = new \AnyMark\ElementTree\Element('b');
		$c = new \AnyMark\ElementTree\Element('c');
		$d = new \AnyMark\ElementTree\Element('d');
		$a->append($b);
		$a->append($c);
		$a->append($d, $b);
		
		$this->assertEquals(array($b, $d, $c), $a->getChildren());
	}

	/**
	 * @test
	 */
	public function canRemoveChild()
	{
		$parent = new \AnyMark\ElementTree\Element('parent');
		$child = new \AnyMark\ElementTree\Element('child');
		$parent->append($child);
		$parent->remove($child);

		$this->assertEquals(array(), $parent->getChildren());
		$this->assertEquals(null, $child->getParent());
	}

	/**
	 * @test
	 */
	public function canReplaceChild()
	{
		$a = new \AnyMark\ElementTree\Element('a');
		$b = new \AnyMark\ElementTree\Element('b');
		$c = new \AnyMark\ElementTree\Element('c');
		$a->append($b);
		$a->replace($c, $b);

		$this->assertEquals(array($c), $a->getChildren());
	}

	/**
	 * @test
	 */
	public function wrapsChildXmlInOwnTags()
	{
		$a = new \AnyMark\ElementTree\Element('a');
		$b = new \AnyMark\ElementTree\Element('b');
		$a->append($b);

		$this->assertEquals('<a><b /></a>', $a->saveXmlStyle());
	}

	/**
	 * @test
	 */
	public function attributesAreInXmlStyleOutput()
	{
		$a = new \AnyMark\ElementTree\Element('a');
		$a->setAttribute('name', 'value');

		$this->assertEquals('<a name="value" />', $a->saveXmlStyle());
	}
}