<?php

namespace AnyMark;

class CustomPatternsTest extends \AnyMark\Tidy
{
	/**
	 * @test
	 */
	public function canChangePatternsFile()
	{
		// given
		$anyMark = \AnyMark\AnyMark::setup();
		$anyMark->registerPlugin(new \AnyMark\CustomPatternFilePlugin());

		// when
		$parsedText = trim($anyMark->parse('foo foo')->toString());

		// then
		$this->assertEquals('bar bar', $parsedText);
	}

	/**
	 * @test
	 */
	public function canAddPatterns()
	{
		// given
		$anyMark = \AnyMark\AnyMark::setup();
		$anyMark->registerPlugin(new \AnyMark\AddPatternsPlugin());

		// when
		$parsedText = trim($anyMark->parse('_foo foo_')->toString());

		// then
		$this->assertEquals('<em>bar bar</em>', $parsedText);
	}
}