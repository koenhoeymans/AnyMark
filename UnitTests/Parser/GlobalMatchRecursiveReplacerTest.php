<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

use \AnyMark\UnitTests\Support;

class AnyMark_Parser_GlobalMatchRecursiveReplacerTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->patternTree = $this->getMock('\\AnyMark\\Pattern\\PatternTree');
		$this->replacer = new \AnyMark\Parser\GlobalMatchRecursiveReplacer(
			$this->patternTree
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
			->will($this->returnValue(new \ElementTree\ElementTreeElement('a')));
		$this->patternTree
			->expects($this->atLeastOnce())
			->method('getSubpatterns')
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

		$this->patternTree
			->expects($this->atLeastOnce())
			->method('getSubpatterns')
			->will($this->returnValue(array($mockPattern)));
		
		$this->assertEquals(
			'text', $this->replacer->parse('text')->toString()
		);
	}

	/**
	 * @test
	 */
	public function presentsTextAfterMatchToPatterns()
	{
		$mockPatternA = new Support\MockPattern('@e@', 'a', 'a');
		$mockPatternB = new Support\MockPattern('@x@', 'b', 'b');

		$this->patternTree
			->expects($this->atLeastOnce())
			->method('getSubpatterns')
			->will($this->returnValue(array($mockPatternA, $mockPatternB)));
		$this->patternTree
			->expects($this->atLeastOnce())
			->method('getSubpatterns')
			->will($this->returnValue(array()));

		$this->assertEquals(
			't<a>a</a><b>b</b>t',
			$this->replacer->parse('text')->toString()
		);
	}

	/**
	 * @test
	 */
	public function presentsMatchesToSubpatterns()
	{
		$mockPattern = new Support\MockPattern('@e@', 'a', 'a');
		$mockSubpattern = new Support\MockPattern('@a@', 'b', 'c');

		$map = array(
			array(null, array($mockPattern)),
			array($mockPattern, array($mockSubpattern)),
			array($mockSubpattern, array())
		);
		$this->patternTree
			->expects($this->atLeastOnce())
			->method('getSubpatterns')
			->will($this->returnValueMap($map));

		$this->assertEquals(
			't<a><b>c</b></a>xt',
			$this->replacer->parse('text')->toString()
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

		$map = array(
			array(null, array($mockPattern)),
			array($mockPattern, array($mockSubpattern1, $mockSubpattern2)),
			array($mockSubpattern1, array()),
			array($mockSubpattern2, array())
		);
		$this->patternTree
			->expects($this->atLeastOnce())
			->method('getSubpatterns')
			->will($this->returnValueMap($map));

		$this->assertEquals(
			't<a><b><c>x</c></b><d><e>y</e></d></a>xt',
			$this->replacer->parse('text')->toString()
		);
	}

	/**
	 * @test
	 */
	public function notifiesObserversOfMatchesHandledByPatterns()
	{
		$mockPattern = $this->getMock('\\AnyMark\\Pattern\\Pattern');
		$mockPattern
			->expects($this->atLeastOnce())
			->method('getRegex')
			->will($this->returnValue('@text@'));
		$mockPattern
			->expects($this->atLeastOnce())
			->method('handleMatch')
			->will($this->returnValue(new \ElementTree\ElementTreeElement('a')));
		$this->patternTree
			->expects($this->atLeastOnce())
			->method('getSubpatterns')
			->will($this->returnValue(array($mockPattern)));

		$event = new \AnyMark\Events\ParsingPatternMatch(
			new \ElementTree\ElementTreeElement('a'), $mockPattern
		);
		$observer = $this->getMock('\\Epa\\Observer');
		$observer->expects($this->once())->method('notify')->with($event);
		$this->replacer->addObserver($observer);

		$this->replacer->parse('text');
	}
}