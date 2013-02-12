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
		$a = new \AnyMark\ComponentTree\Element('a');
		$a->setAttribute('href', "mailto:me@xmpl.com");
		$a->append(new \AnyMark\ComponentTree\Text("me@xmpl.com"));

		$this->assertEquals(
			$a,
			$this->applyPattern("Mail to <me@xmpl.com>.")
		);
	}

	/**
	 * @test
	 */
	public function withoutAngledBracketsNoMailLinkIsCreated()
	{
		$text = "Mail to me@example.com, it's an email address link.";
		preg_match($this->getPattern()->getRegex(), $text, $match);
		$this->assertTrue(empty($match));
	}

	/**
	 * @test
	 */
	public function anUrlBetweenLesserThanAndreaterThanSignIsAutolinked()
	{
		$a = new \AnyMark\ComponentTree\Element('a');
		$a->setAttribute('href', "http://example.com");
		$a->append(new \AnyMark\ComponentTree\Text("http://example.com"));

		$this->assertEquals(
			$a,
			$this->applyPattern("Visit <http://example.com>.")
		);
	}
}