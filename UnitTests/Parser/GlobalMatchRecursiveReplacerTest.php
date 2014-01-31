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
	public function presentsTextAfterMatchToSamePattern()
	{
		$elementTree = new \ElementTree\ElementTree();
		$element = $elementTree->createElement('a');
		$element->append($elementTree->createText('o'));
		$mockPatternA = $this->getMock('\\AnyMark\\Pattern\\Pattern');
		$mockPatternA
			->expects($this->atLeastOnce())
			->method('getRegex')
			->will($this->returnValue('@e@'));
		$mockPatternA
			->expects($this->exactly(2))
			->method('handleMatch')
			->will($this->returnValue($element));
		$mockPatternB = new Support\MockPattern('@e@', 'b', 'b');

		$patternMap = array(
			array(null, array($mockPatternA, $mockPatternB)),
			array($mockPatternA, array()),
			array($mockPatternB, array())
		);
		$this->patternTree
			->expects($this->atLeastOnce())
			->method('getSubpatterns')
			->will($this->returnValueMap($patternMap));

		$this->assertEquals(
			't<a>o</a><a>o</a>th',
			$this->replacer->parse('teeth')->toString()
		);
	}

	/**
	 * @test
	 */
	public function presentsTextLeftToNextPattern()
	{
		$elementA = new \ElementTree\ElementTreeElement('a');
		$elementA->append(new \ElementTree\ElementTreeText('o'));
		$mockPatternA = $this->getMock('\\AnyMark\\Pattern\\Pattern');
			$mockPatternA
			->expects($this->exactly(2))
			->method('getRegex')
			->will($this->returnValue('@i@'));
			$mockPatternA
			->expects($this->exactly(1))
			->method('handleMatch')
			->will($this->returnValue($elementA));
		$elementB = new \ElementTree\ElementTreeElement('b');
		$elementB->append(new \ElementTree\ElementTreeText('b'));
		$mockPatternB = $this->getMock('\\AnyMark\\Pattern\\Pattern');
		$mockPatternB
			->expects($this->atLeastOnce())
			->method('getRegex')
			->will($this->returnValue('@t@'));
		$mockPatternB
			->expects($this->exactly(2))
			->method('handleMatch')
			->will($this->returnValue($elementB));

		$patternMap = array(
			array(null, array($mockPatternA, $mockPatternB)),
			array($mockPatternA, array()),
			array($mockPatternB, array())
		);
		$this->patternTree
			->expects($this->atLeastOnce())
			->method('getSubpatterns')
			->will($this->returnValueMap($patternMap));

		$this->assertEquals(
			'<b>b</b><a>o</a><b>b</b>',
			$this->replacer->parse('tit')->toString()
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
		$element = new \ElementTree\ElementTreeElement('a');
		$mockPattern = $this->getMock('\\AnyMark\\Pattern\\Pattern');
		$mockPattern
			->expects($this->atLeastOnce())
			->method('getRegex')
			->will($this->returnValue('@text@'));
		$mockPattern
			->expects($this->atLeastOnce())
			->method('handleMatch')
			->will($this->returnValue($element));
		$this->patternTree
			->expects($this->atLeastOnce())
			->method('getSubpatterns')
			->will($this->returnValue(array($mockPattern)));

		$event = new \AnyMark\Events\ParsingPatternMatch(
			$element, $mockPattern
		);
		$observer = $this->getMock('\\Epa\\Observer');
		$observer->expects($this->once())->method('notify')->with($event);
		$this->replacer->addObserver($observer);

		$this->replacer->parse('text');
	}
}