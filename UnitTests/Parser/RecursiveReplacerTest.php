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

	private function domDocWith($xml)
	{
		$domDoc = new \DOMDocument();
		$domDoc->loadXML($xml);
		return $domDoc;
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
			->will($this->returnValue(new \DOMElement('a')));
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
			$this->domDocWith('<doc>text</doc>'),
			$this->replacer->parse('text')
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
			$this->domDocWith('<doc>t<a>a</a><b>b</b>t</doc>'),
			$this->replacer->parse('text')
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
			$this->domDocWith('<doc>t<a><b>c</b></a>xt</doc>'),
			$this->replacer->parse('text')
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
			$this->domDocWith('<doc>t<a><b><c>x</c></b><d><e>y</e></d></a>xt</doc>'),
			$this->replacer->parse('text')
		);
	}
}