<?php

namespace Anymark\Pattern\Patterns;

class HyperlinkTest extends \AnyMark\PatternReplacementAssertions
{
    public function setup()
    {
        $this->linkDefinitions = $this->getMock(
            '\\AnyMark\\Plugins\\LinkDefinitionCollector\\LinkDefinitionCollector'
        );
        $this->internalUrlBuilder = $this->getMock(
            '\\AnyMark\\Util\\InternalUrlBuilder'
        );
        $this->hyperlink = new \AnyMark\Pattern\Patterns\Hyperlink(
            $this->linkDefinitions,
            $this->internalUrlBuilder
        );
    }

    public function getPattern()
    {
        return $this->hyperlink;
    }

    public function createDomForLink($url, $text, $title = null)
    {
        $a = $this->elementTree()->createElement('a');
        $a->setAttribute('href', $url);
        if ($title) {
            $a->setAttribute('title', $title);
        }
        $a->append($this->elementTree()->createText($text));

        return $a;
    }

    /**
     * @test
     */
    public function anUrlHasAnchoTextInSquareBracketsFollowedByTheLinkInParentheses()
    {
        $this->internalUrlBuilder
            ->expects($this->once())
            ->method('urlTo')->with('http://example.com')
            ->will($this->returnValue('http://example.com'));
        $text = "Visit [my site](http://example.com) for info.";
        $expected = $this->createDomForLink('http://example.com', 'my site');
        $this->assertEquals($expected, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function aLinkTitleCanBeSpecifiedAfterTheUrlInDoubleQuotes()
    {
        $text = "Visit [my site](http://example.com \"title\") for info.";
        $this->internalUrlBuilder
            ->expects($this->once())
            ->method('urlTo')->with('http://example.com')
            ->will($this->returnValue('http://example.com'));
        $expected = $this->createDomForLink('http://example.com', 'my site', 'title');
        $this->assertEquals($expected, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function aLinkTitleCanBeSpecifiedAfterTheUrlBetweenSingleQuotes()
    {
        $text = "Visit [my site](http://example.com 'title') for info.";
        $this->internalUrlBuilder
            ->expects($this->once())
            ->method('urlTo')->with('http://example.com')
            ->will($this->returnValue('http://example.com'));
        $expected = $this->createDomForLink('http://example.com', 'my site', 'title');
        $this->assertEquals($expected, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function theUrlCanBePlacedElsewhereWhenLinkTextIsFollowedBySquareBracketedTitleAsReference()
    {
        $this->linkDefinitions
            ->expects($this->once())
            ->method('get')->with('1')
            ->will($this->returnValue(
                new \AnyMark\Pattern\Patterns\LinkDefinition('1', 'http://example.com')
            ));
        $this->internalUrlBuilder
            ->expects($this->once())
            ->method('urlTo')->with('http://example.com')
            ->will($this->returnValue('http://example.com'));
        $text = "Visit [my site][1] for info.\n\n"
            ."paragraph\n\n";
        $expected = $this->createDomForLink('http://example.com', 'my site');
        $this->assertEquals($expected, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function titleTextCanComeFromLinkDefinition()
    {
        $this->linkDefinitions
            ->expects($this->once())
            ->method('get')->with('1')
            ->will($this->returnValue(
                new \AnyMark\Pattern\Patterns\LinkDefinition('1', 'http://example.com', 'title')
            ));
        $this->internalUrlBuilder
            ->expects($this->once())
            ->method('urlTo')->with('http://example.com')
            ->will($this->returnValue('http://example.com'));
        $text = "Visit [my site][1] for info.\n\n"
            ."paragraph\n\n";
        $expected = $this->createDomForLink('http://example.com', 'my site', 'title');
        $this->assertEquals($expected, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function linkDefinitionMayBePlacedASpaceAfterAnchorText()
    {
        $this->linkDefinitions
            ->expects($this->once())
            ->method('get')->with('1')
            ->will($this->returnValue(
                new \AnyMark\Pattern\Patterns\LinkDefinition('1', 'http://example.com')
            ));
        $this->internalUrlBuilder
            ->expects($this->once())
            ->method('urlTo')->with('http://example.com')
            ->will($this->returnValue('http://example.com'));
        $text = "Visit [my site] [1] for info.\n\n"
            ."paragraph\n\n";
        $expected = $this->createDomForLink('http://example.com', 'my site');
        $this->assertEquals($expected, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function linkDefinitionMayBeLeftBlankForUseOfAnchorTextAsLinkDefinition()
    {
        $this->linkDefinitions
            ->expects($this->once())
            ->method('get')->with('my site')
            ->will($this->returnValue(
                new \AnyMark\Pattern\Patterns\LinkDefinition('my site', 'http://example.com')
            ));
        $this->internalUrlBuilder
            ->expects($this->once())
            ->method('urlTo')->with('http://example.com')
            ->will($this->returnValue('http://example.com'));
        $text = "Visit [my site] [] for info.\n\n"
            ."paragraph\n\n";
        $expected = $this->createDomForLink('http://example.com', 'my site');
        $this->assertEquals($expected, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function linkCanBeInTextWithinBrackets()
    {
        $this->linkDefinitions
            ->expects($this->once())
            ->method('get')->with('my site')
            ->will($this->returnValue(
                new \AnyMark\Pattern\Patterns\LinkDefinition('my site', 'http://example.com')
            ));
        $this->internalUrlBuilder
            ->expects($this->once())
            ->method('urlTo')->with('http://example.com')
            ->will($this->returnValue('http://example.com'));
        $text = "You may [visit [my site] [] for] info.\n\n"
            ."paragraph\n\n";
        $expected = $this->createDomForLink('http://example.com', 'my site');
        $this->assertEquals($expected, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function onlyALinkDefinitionMayBeUsed()
    {
        $this->linkDefinitions
            ->expects($this->once())
            ->method('get')->with('my site')
            ->will($this->returnValue(
                new \AnyMark\Pattern\Patterns\LinkDefinition('my site', 'http://example.com')
            ));
        $this->internalUrlBuilder
            ->expects($this->once())
            ->method('urlTo')->with('http://example.com')
            ->will($this->returnValue('http://example.com'));
        $text = "Visit [my site] for info.\n\n"
            ."paragraph\n\n";
        $expected = $this->createDomForLink('http://example.com', 'my site');
        $this->assertEquals($expected, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function anchorTextCanContainATextLink()
    {
        $text = "Visit [site http://x.com](http://y.com \"title\") for info.";
        $this->internalUrlBuilder
            ->expects($this->once())
            ->method('urlTo')->with('http://y.com')
            ->will($this->returnValue('http://y.com'));
        $expected = $this->createDomForLink('http://y.com', 'site http://x.com', 'title');
        $this->assertEquals($expected, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function squareBracketsInLinksAreOk()
    {
        $this->internalUrlBuilder
            ->expects($this->once())
            ->method('urlTo')->with('http://example.com?x=[y]&amp;foo=[bar]')
            ->will($this->returnValue('http://example.com?x=[y]&amp;foo=[bar]'));
        $text = "Visit [my website](http://example.com?x=[y]&amp;foo=[bar]) for info.";
        $expected = $this->createDomForLink('http://example.com?x=[y]&amp;foo=[bar]', 'my website');
        $this->assertEquals($expected, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function handlesInvalidNesting()
    {
        $this->internalUrlBuilder
            ->expects($this->once())
            ->method('urlTo')->with('url')
            ->will($this->returnValue('url'));
        $text = "Visit [[Link](url)](url) for info.";
        $expected = $this->createDomForLink('url', '[Link](url)');
        $this->assertEquals($expected, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function linksCanBeRelative()
    {
        $this->internalUrlBuilder
            ->expects($this->once())
            ->method('urlTo')->with('x')
            ->will($this->returnValue('x.html'));
        $text = "See page [x](x) for info.";
        $expected = $this->createDomForLink('x.html', 'x');
        $this->assertEquals($expected, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function linkIsRelativeIfItContainsOnlyAlphaNumForwardSlashesBeforeAnOptionalNumberSign()
    {
        $this->internalUrlBuilder
            ->expects($this->once())
            ->method('urlTo')->with('x/6/f4#f')
            ->will($this->returnValue('x.html'));
        $text = "See page [x/6/f4#f](x/6/f4#f) for info.";
        $expected = $this->createDomForLink('x.html', 'x/6/f4#f');
        $this->assertEquals($expected, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function anchorTextAndUrlCanBePlacedOnDifferentLines()
    {
        $this->internalUrlBuilder
            ->expects($this->once())
            ->method('urlTo')->with('link')
            ->will($this->returnValue('link.html'));
        $text = "See page [link]\n(link) for info.";
        $expected = $this->createDomForLink('link.html', 'link');
        $this->assertEquals($expected, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function urlCanContainSpace()
    {
        $this->internalUrlBuilder
            ->expects($this->once())
            ->method('urlTo')->with('url://with spaces')
            ->will($this->returnValue('link.html'));
        $text = "See page [link](<url://with spaces>) for info.";
        $expected = $this->createDomForLink('link.html', 'link');
        $this->assertEquals($expected, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function mayNotHaveNonSpaceBefore()
    {
        $text = "See page[link](http://url) for info.";
        $this->assertEquals(null, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function mayNotHaveNonSpaceAfter()
    {
        $text = "See page [link](http://url)for info.";
        $this->assertEquals(null, $this->applyPattern($text));
    }
}
