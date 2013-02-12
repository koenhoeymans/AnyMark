<?php

use AnyMark\ComponentTree\Component;

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class ComponentImp extends Component
{
	public function saveXmlStyle() {}
	public function hasChildren() {}
}

class AnyMark_ComponentTree_ComponentTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->eTree = new ComponentImp();
	}

	/**
	 * @test
	 */
	public function createsElement()
	{
		$this->assertEquals(
			new \AnyMark\ComponentTree\Element('foo'),
			$this->eTree->createElement('foo')
		);
	}

	/**
	 * @test
	 */
	public function createsText()
	{
		$this->assertEquals(
			new \AnyMark\ComponentTree\Text('foo'),
			$this->eTree->createText('foo')
		);
	}

	/**
	 * @test
	 */
	public function hasNoParentElementIfNotAppended()
	{
		$this->assertEquals(null, $this->eTree->getParent());
	}

	/**
	 * @test
	 */
	public function elementsInWholeTreeCanBeSelectedWithCallback()
	{
		$callback = function(\AnyMark\ComponentTree\Component $elementTree) {
			if ($elementTree !== $this->eTree)
			{
				$this->assertFalse(true);
			}
		};

		$this->eTree->query($callback);
	}
}