<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Plugins_HtmlEntities_HtmlEntitiesTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->plugin = new \AnyMark\Plugins\HtmlEntities\HtmlEntities();
	}

	/**
	 * @test
	 */
	public function replacesEntitiesInText()
	{
		$tree = new \ElementTree\ElementTree();
		$text = $tree->createText('at&t');
		$tree->append($text);

		$this->plugin->handleTree($tree);

		$this->assertEquals('at&amp;t', $tree->toString());
	}

	/**
	 * @test
	 */
	public function replacesEntitiesInAttributeValues()
	{
		$tree = new \ElementTree\ElementTree();
		$div = $tree->createElement('div');
		$div->setAttribute('id', 'at&t');
		$tree->append($div);

		$this->plugin->handleTree($tree);
	
		$this->assertEquals('<div id="at&amp;t" />', $tree->toString());
	}
}