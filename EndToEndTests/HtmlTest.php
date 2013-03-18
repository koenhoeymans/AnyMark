<?php

require_once('TestHelper.php');

class AnyMark_EndToEndTests_HtmlTest extends \AnyMark\EndToEndTests\Support\Tidy
{
	/**
	 * @test
	 */
	public function HtmlIsAllowed()
	{
		// given
		$anyMark = \AnyMark\AnyMark::createWith(\AnyMark\AnyMark::defaultSetup());
		$text = file_get_contents(__DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'HtmlTest.txt');

		// when
		$parsedText = $anyMark->parse($text)->toString();

		// then
		$this->assertEquals(
			$this->tidy(file_get_contents(
				__DIR__
				. DIRECTORY_SEPARATOR . 'Support'
				. DIRECTORY_SEPARATOR . 'HtmlTest.html'
			)),
			$this->tidy($parsedText)
		);
	}
}