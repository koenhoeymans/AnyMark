<?php

require_once('TestHelper.php');

class AnyMark_EndToEndTests_SampleTextTest extends \AnyMark\EndToEndTests\Support\Tidy
{
	/**
	 * @test
	 */
	public function acceptsCustomPatternsFile()
	{
		// given
		$anyMark = \AnyMark\AnyMark::createWith(\AnyMark\AnyMark::defaultSetup());
		$anyMark->setPatternsFile(__DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'AnyMark'
			. DIRECTORY_SEPARATOR . 'Patterns.php');
		$text = file_get_contents(__DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'SampleText.txt');
		
		// when
		$parsedText = $anyMark->parse($text)->toString();
		
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