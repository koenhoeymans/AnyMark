<?php

require_once('TestHelper.php');

class AnyMark_EndToEndTests_CustomPatternsTest extends \AnyMark\EndToEndTests\Support\Tidy
{
	/**
	 * @test
	 */
	public function canChangePatternsFile()
	{
		// given
		$patternsFile = __DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'CustomPatterns.php';
		$anyMark = \AnyMark\AnyMark::setup();
		$anyMark->registerPlugin(
			new \AnyMark\EndToEndTests\Support\CustomPatternFilePlugin()
		);

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
		$patternsFile = __DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'CustomPatterns.php';
		$anyMark = \AnyMark\AnyMark::setup();
		$anyMark->registerPlugin(
			new \AnyMark\EndToEndTests\Support\AddPatternsPlugin()
		);

		// when
		$parsedText = trim($anyMark->parse('_foo foo_')->toString());

		// then
		$this->assertEquals('<i>bar bar</i>', $parsedText);
	}
}