<?php

require_once('TestHelper.php');

class AnyMark_EndToEndTests_SampleTextTest extends \AnyMark\EndToEndTests\Support\Tidy
{
	/**
	 * @test
	 */
	public function sampleText()
	{
		// given
		$anyMark = \AnyMark\AnyMark::setup()->get('AnyMark\\AnyMark');
		$text = file_get_contents(__DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'SampleText.txt');

		// when
		$parsedText = $anyMark->saveXml($anyMark->parse($text));

		// then
		$this->assertEquals(
			$this->tidy(file_get_contents(
				__DIR__
				. DIRECTORY_SEPARATOR . 'Support'
				. DIRECTORY_SEPARATOR . 'SampleText.html'
			)),
			$this->tidy($parsedText)
		);
	}
}