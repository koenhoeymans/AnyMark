<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Pattern_Patterns_HyperlinkTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->linkDefinitions = $this->getMock(
			'\\AnyMark\\Processor\\Processors\\LinkDefinitionCollector'
		);
		$this->internalUrlBuilder = $this->getMock(
			'\\AnyMark\\Util\\InternalUrlBuilder'
		);
		$this->hyperlink = new \AnyMark\Pattern\Patterns\Hyperlink(
			$this->linkDefinitions, $this->internalUrlBuilder
		);
	}

	public function getPattern()
	{
		return $this->hyperlink;
	}

	public function createDomForLink($url, $text, $title = null)
	{
		$domDoc = new \DOMDocument();
		$domEl = $domDoc->createElement('a', $text);
		$domEl->setAttribute('href', $url);
		$domDoc->appendChild($domEl);
		if ($title)
		{
			$domEl->setAttribute('title', $title);
		}

		return $domEl;
	}

	/**
	 * @test
	 */
	public function anUrlHasAnchoTextInSquareBracketsFollowedByTheLinkInParentheses()
	{
		$text = "Visit [my site](http://example.com) for info.";
		$dom = $this->createDomForLink('http://example.com', 'my site');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function aLinkTitleCanBeSpecifiedAfterTheUrlInDoubleQuotes()
	{
		$text = "Visit [my site](http://example.com \"title\") for info.";
		$dom = $this->createDomForLink('http://example.com', 'my site', 'title');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function aLinkTitleCanBeSpecifiedAfterTheUrlBetweenSingleQuotes()
	{
		$text = "Visit [my site](http://example.com 'title') for info.";
		$dom = $this->createDomForLink('http://example.com', 'my site', 'title');
		$this->assertCreatesDomFromText($dom, $text);
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
				new \AnyMark\Pattern\Patterns\LinkDefinition('1', 'http://example.com')));
		$text = "Visit [my site][1] for info.\n\n"
			. "paragraph\n\n";
		$dom = $this->createDomForLink('http://example.com', 'my site');
		$this->assertCreatesDomFromText($dom, $text);
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
				new \AnyMark\Pattern\Patterns\LinkDefinition('1', 'http://example.com', 'title')));
		$text = "Visit [my site][1] for info.\n\n"
			. "paragraph\n\n";
		$dom = $this->createDomForLink('http://example.com', 'my site', 'title');
		$this->assertCreatesDomFromText($dom, $text);
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
				new \AnyMark\Pattern\Patterns\LinkDefinition('1', 'http://example.com')));
		$text = "Visit [my site] [1] for info.\n\n"
			. "paragraph\n\n";
		$dom = $this->createDomForLink('http://example.com', 'my site');
		$this->assertCreatesDomFromText($dom, $text);
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
		new \AnyMark\Pattern\Patterns\LinkDefinition('my site', 'http://example.com')));
		$text = "Visit [my site] [] for info.\n\n"
			. "paragraph\n\n";
		$dom = $this->createDomForLink('http://example.com', 'my site');
		$this->assertCreatesDomFromText($dom, $text);
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
		new \AnyMark\Pattern\Patterns\LinkDefinition('my site', 'http://example.com')));
		$text = "Visit [my site] for info.\n\n"
			. "paragraph\n\n";
		$dom = $this->createDomForLink('http://example.com', 'my site');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function anchorTextCanContainATextLink()
	{
		$text = "Visit [site http://x.com](http://y.com \"title\") for info.";
		$dom = $this->createDomForLink('http://y.com', 'site http://x.com', 'title');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function squareBracketsInLinksAreOk()
	{
		$text = "Visit [my website](http://example.com?x=[y]&amp;foo=[bar]) for info.";
		$dom = $this->createDomForLink('http://example.com?x=[y]&amp;foo=[bar]', 'my website');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function linksCanBeRelative()
	{
		$this->internalUrlBuilder
			->expects($this->once())
			->method('createRelativeLink')->with('x')
			->will($this->returnValue('x.html'));
		$text = "See page [x](x) for info.";
		$dom = $this->createDomForLink('x.html', 'x');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function linkIsRelativeIfItContainsOnlyAlphaNumForwardSlashesBeforeAnOptionalNumberSign()
	{
		$this->internalUrlBuilder
			->expects($this->once())
			->method('createRelativeLink')->with('x/6/f4#f')
			->will($this->returnValue('x.html'));
		$text = "See page [x/6/f4#f](x/6/f4#f) for info.";
		$dom = $this->createDomForLink('x.html', 'x/6/f4#f');
		$this->assertCreatesDomFromText($dom, $text);
	}
}