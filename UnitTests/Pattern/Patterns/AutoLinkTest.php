<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Pattern_Patterns_AutoLinkTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->pattern = new \AnyMark\Pattern\Patterns\AutoLink();
	}

	public function getPattern()
	{
		return $this->pattern;
	}

	/**
	 * @test
	 */
	public function anEmailAddressIsLinkedWhenPlacedBetweenALesserThanAndGreaterThanSign()
	{
		$text = "Mail to <me@xmpl.com>.";
		$domDoc = new \DOMDocument();
		$domEl = $domDoc->appendChild(new \DOMElement('a', 'me@xmpl.com'));
		$domEl->setAttribute('href', 'mailto:me@xmpl.com');
		$this->assertCreatesDomFromText($domEl, $text);
	}

	/**
	 * @test
	 */
	public function withoutAngledBracketsNoMailLinkIsCreated()
	{
		$text = "Mail to me@example.com, it's an email address link.";
		$this->assertDoesNotCreateDomFromText($text);
	}

	/**
	 * @test
	 */
	public function anUrlBetweenLesserThanAndreaterThanSignIsAutolinked()
	{
		$text = "Visit <http://example.com>.";
		$domDoc = new \DOMDocument();
		$domEl = $domDoc->appendChild(new \DOMElement('a', 'http://example.com'));
		$domEl->setAttribute('href', 'http://example.com');
		$this->assertCreatesDomFromText($domEl, $text);
	}

	/**
	 * @test
	 */
	public function handlesInternationalDomainNames()
	{
		$text = "Visit <http://example.com>.";
		$domDoc = new \DOMDocument();
		$domEl = $domDoc->appendChild(new \DOMElement('a', 'http://example.com'));
		$domEl->setAttribute('href', 'http://example.com');
		$this->assertCreatesDomFromText($domEl, $text);
	}
}