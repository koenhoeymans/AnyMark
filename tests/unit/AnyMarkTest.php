<?php

namespace AnyMark;

class AnyMarkTest extends \PHPUnit\Framework\TestCase
{
    public function setup()
    {
        $this->parser = $this->createMock('\\AnyMark\\Parser\\Parser');
        $this->eventDispatcher = $this->createMock('\\Epa\\Api\\EventDispatcher');
        $this->patternConfig = $this->createMock('\\AnyMark\\Pattern\\FileArrayPatternConfig');
        $this->anyMark = new \AnyMark\AnyMark(
            $this->parser,
            $this->eventDispatcher,
            $this->patternConfig
        );
    }

    /**
     * @test
     */
    public function eventsAreThrown()
    {
        $observer = $this->createMock('\\Epa\\Api\\Observer');
        $this->anyMark->addObserver($observer);

        $this->parser
            ->expects($this->once())
            ->method('parse')
            ->will($this->returnValue(new \ElementTree\ElementTree()));

        $observer
            ->expects($this->at(0))
            ->method('notify')
            ->with(new \AnyMark\Events\SetPatternConfig($this->patternConfig));
        $observer
            ->expects($this->at(1))
            ->method('notify')
            ->with(new \AnyMark\Events\PatternConfigLoaded($this->patternConfig));
        $observer
            ->expects($this->at(2))
            ->method('notify')
            ->with(new \AnyMark\Events\BeforeParsing("text\n\n"));
        $observer
            ->expects($this->at(3))
            ->method('notify')
            ->with(new \AnyMark\Events\AfterParsing(new \ElementTree\ElementTree()));

        $this->anyMark->parse('text');
    }

    /**
     * @test
     */
    public function returnsParsingResultAsElementTree()
    {
        $this->parser
            ->expects($this->once())
            ->method('parse')
            ->will($this->returnValue(new \ElementTree\ElementTree()));

        $this->assertTrue(
            $this->anyMark->parse('text') instanceof \ElementTree\ElementTree
        );
    }
}
