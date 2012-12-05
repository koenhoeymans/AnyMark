<?php

require_once('TestHelper.php');

class AnyMark_EndToEndTests_SpecialCharacterTest extends \AnyMark\EndToEndTests\Support\Tidy
{
	/**
	 * @test
	 */
	public function specialCharacterTest()
	{
		// given
		$anyMark = \AnyMark\AnyMark::setup();
		$text = file_get_contents(__DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'SpecialCharacterTest.txt');

		// when
		$parsedText = $anyMark->parse($text);

		// then
		$this->assertEquals(
			$this->tidy(file_get_contents(
				__DIR__
				. DIRECTORY_SEPARATOR . 'Support'
				. DIRECTORY_SEPARATOR . 'SpecialCharacterTest.html'
			)),
			$this->tidy($parsedText)
		);
	}
}