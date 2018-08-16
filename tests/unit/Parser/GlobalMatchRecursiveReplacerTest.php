<?php

namespace AnyMark\Parser;

class GlobalMatchRecursiveReplacerTest extends \PHPUnit\Framework\TestCase
{
    public function setup()
    {
        $this->patternTree = $this->createMock('\\AnyMark\\Pattern\\PatternTree');
        $this->replacer = new \AnyMark\Parser\GlobalMatchRecursiveReplacer(
            $this->patternTree
        );
    }

    /**
     * @test
     */
    public function appliesPatternsToText()
    {
        $mockPattern = $this->createMock('\\AnyMark\\Pattern\\Pattern');
        $mockPattern
            ->expects($this->atLeastOnce())
            ->method('getRegex')
            ->will($this->returnValue('@text@'));
        $mockPattern
            ->expects($this->atLeastOnce())
            ->method('handleMatch')
            ->will($this->returnValue(new \ElementTree\Element('a')));
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
        $mockPattern = $this->createMock('\\AnyMark\\Pattern\\Pattern');
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
            'text',
            $this->replacer->parse('text')->toString()
        );
    }

    /**
     * @test
     */
    public function presentsTextAfterMatchToSamePattern()
    {
        $elementTree = new \ElementTree\ElementTree();
        $mockPatternA = $this->createMock('\\AnyMark\\Pattern\\Pattern');
        $mockPatternA
            ->expects($this->atLeastOnce())
            ->method('getRegex')
            ->will($this->returnValue('@e@'));
        $element = $elementTree->createElement('a');
        $element->append($elementTree->createText('o'));
        $mockPatternA
            ->expects($this->at(1))
            ->method('handleMatch')
            ->will($this->returnValue($element));
        $element = $elementTree->createElement('a');
        $element->append($elementTree->createText('o'));
        $mockPatternA
            ->expects($this->at(3))
            ->method('handleMatch')
            ->will($this->returnValue($element));
        $mockPatternB = new \AnyMark\MockPattern('@e@', 'b', 'b');

        $patternMap = array(
            array(null, array($mockPatternA, $mockPatternB)),
            array($mockPatternA, array()),
            array($mockPatternB, array()),
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
        $elementA = new \ElementTree\Element('a');
        $elementA->append(new \ElementTree\Text('o'));
        $mockPatternA = $this->createMock('\\AnyMark\\Pattern\\Pattern');
        $mockPatternA
            ->expects($this->exactly(2))
            ->method('getRegex')
            ->will($this->returnValue('@i@'));
        $mockPatternA
            ->expects($this->exactly(1))
            ->method('handleMatch')
            ->will($this->returnValue($elementA));
        $mockPatternB = $this->createMock('\\AnyMark\\Pattern\\Pattern');
        $mockPatternB
            ->expects($this->atLeastOnce())
            ->method('getRegex')
            ->will($this->returnValue('@t@'));
        $elementB = new \ElementTree\Element('b');
        $elementB->append(new \ElementTree\Text('b'));
        $mockPatternB
            ->expects($this->at(1))
            ->method('handleMatch')
            ->will($this->returnValue($elementB));
        $elementB = new \ElementTree\Element('b');
        $elementB->append(new \ElementTree\Text('b'));
        $mockPatternB
            ->expects($this->at(4))
            ->method('handleMatch')
            ->will($this->returnValue($elementB));

        $patternMap = array(
            array(null, array($mockPatternA, $mockPatternB)),
            array($mockPatternA, array()),
            array($mockPatternB, array()),
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
        $mockPattern = new \AnyMark\MockPattern('@e@', 'a', 'a');
        $mockSubpattern = new \AnyMark\MockPattern('@a@', 'b', 'c');

        $map = array(
            array(null, array($mockPattern)),
            array($mockPattern, array($mockSubpattern)),
            array($mockSubpattern, array()),
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
        $mockPattern = new \AnyMark\MockPatternCreatingMultiNodes(
            '@e@',
            'a',
            array('tag' => 'b', 'text' => 'foo'),
            array('tag' => 'd', 'text' => 'bar')
        );
        $mockSubpattern1 = new \AnyMark\MockPattern('@foo@', 'c', 'x');
        $mockSubpattern2 = new \AnyMark\MockPattern('@bar@', 'e', 'y');

        $map = array(
            array(null, array($mockPattern)),
            array($mockPattern, array($mockSubpattern1, $mockSubpattern2)),
            array($mockSubpattern1, array()),
            array($mockSubpattern2, array()),
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
        $element = new \ElementTree\Element('a');
        $mockPattern = $this->createMock('\\AnyMark\\Pattern\\Pattern');
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
            $element,
            $mockPattern
        );
        $observer = $this->createMock('\\Epa\\Api\\Observer');
        $observer->expects($this->once())->method('notify')->with($event);
        $this->replacer->addObserver($observer);

        $this->replacer->parse('text');
    }
}
