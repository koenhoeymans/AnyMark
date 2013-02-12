<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

use \AnyMark\UnitTests\Support;

class AnyMark_Parser_RecursiveReplacerTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Two diffulties in testing this:
	 * - created nodes must be associated with same document
	 * - mock callbacks clones callback arguments
	 */
	public function setup()
	{
		$this->patternList = $this->getMockBuilder('\\AnyMark\\Pattern\\PatternList')
			->disableOriginalConstructor()
			->getMock();
		$this->replacer = new \AnyMark\Parser\RecursiveReplacer(
			$this->patternList
		);
	}

	/**
	 * @test
	 */
	public function appliesPatternsToText()
	{
		$mockPattern = $this->getMock('\\AnyMark\\Pattern\\Pattern');
		$mockPattern
			->expects($this->atLeastOnce())
			->method('getRegex')
			->will($this->returnValue('@text@'));
		$mockPattern
			->expects($this->atLeastOnce())
			->method('handleMatch')
			->will($this->returnValue(new \AnyMark\ComponentTree\Element('a')));
		$this->patternList
			->expects($this->atLeastOnce())
			->method('getPatterns')
			->will($this->returnValue(array($mockPattern)));

		$this->replacer->parse('<doc>text
</doc>');
	}

	/**
	 * @test
	 */
	public function afterARegexMatchAPatternCanDecideItIsAFalsePositive()
	{
		$mockPattern = $this->getMock('\\AnyMark\\Pattern\\Pattern');
		$mockPattern
			->expects($this->atLeastOnce())
			->method('getRegex')
			->will($this->returnValue('@e@'));
		$mockPattern
			->expects($this->atLeastOnce())
			->method('handleMatch')
			->will($this->returnValue(null));

		$this->patternList
			->expects($this->atLeastOnce())
			->method('getPatterns')
			->will($this->returnValue(array($mockPattern)));
		
		$this->assertEquals(
			'text', $this->replacer->parse('text')->saveXmlStyle()
		);
	}

	/**
	 * @test
	 */
	public function presentsTextAfterMatchToPatterns()
	{
		$mockPatternA = new Support\MockPattern('@e@', 'a', 'a');
		$mockPatternB = new Support\MockPattern('@x@', 'b', 'b');

		$this->patternList
			->expects($this->atLeastOnce())
			->method('getPatterns')
			->will($this->returnValue(array($mockPatternA, $mockPatternB)));
		$this->patternList
			->expects($this->atLeastOnce())
			->method('getSubpatterns')
			->will($this->returnValue(array()));

		$this->assertEquals(
			't<a>a</a><b>b</b>t',
			$this->replacer->parse('text')->saveXmlStyle()
		);
	}

	/**
	 * @test
	 */
	public function presentsMatchesToSubpatterns()
	{
		$mockPattern = new Support\MockPattern('@e@', 'a', 'a');
		$mockSubpattern = new Support\MockPattern('@a@', 'b', 'c');

		$this->patternList
			->expects($this->any())
			->method('getPatterns')
			->will($this->returnValue(array($mockPattern)));
		$this->patternList
			->expects($this->any())
			->method('getSubpatterns')
			->will($this->returnValue(array($mockSubpattern)));

		$this->assertEquals(
			't<a><b>c</b></a>xt',
			$this->replacer->parse('text')->saveXmlStyle()
		);
	}

	/**
	 * @test
	 */
	public function aPatternCanReturnMultipleTextNodes()
	{
		$mockPattern = new Support\MockPatternCreatingMultiNodes(
			'@e@',
			'a',
			array('tag' => 'b', 'text' => 'foo'),
			array('tag' => 'd', 'text' => 'bar')
		);
		$mockSubpattern1 = new Support\MockPattern('@foo@', 'c', 'x');
		$mockSubpattern2 = new Support\MockPattern('@bar@', 'e', 'y');

		$this->patternList
			->expects($this->any())
			->method('getPatterns')
			->will($this->returnValue(array($mockPattern)));
		$this->patternList
			->expects($this->any())
			->method('getSubpatterns')
			->will($this->returnValue(array($mockSubpattern1, $mockSubpattern2)));

		$this->assertEquals(
			't<a><b><c>x</c></b><d><e>y</e></d></a>xt',
			$this->replacer->parse('text')->saveXmlStyle()
		);
	}
}