<?php

require_once('TestHelper.php');

class AnyMark_EndToEndTests_CustomPatternsTest extends \AnyMark\EndToEndTests\Support\Tidy
{
	/**
	 * @test
	 */
	public function customPatternsFile()
	{
		// given
		$anyMark = \AnyMark\AnyMark::setup()->get('AnyMark\\AnyMark');
		$anyMark->setPatternsFile(__DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'CustomPatterns.php');

		// when
		$parsedText = trim($anyMark->parse('**foo *_bar_* baz**')->toString());

		// then
		$this->assertEquals(
			'<strong>foo <em>_bar_</em> baz</strong>', $parsedText
		);
	}
}