<?php

require_once('TestHelper.php');

class AnyMark_EndToEndTests_IndentationTest extends \AnyMark\EndToEndTests\Support\Tidy
{
	/**
	 * @test
	 */
	public function indentation()
	{
		// given
		$anyMark = \AnyMark\AnyMark::setup()->get('AnyMark\\AnyMark');
		$text = file_get_contents(__DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'Indentation.txt');

		// when
		$parsedText = $anyMark->saveXml($anyMark->parse($text));

		// then
		$this->assertEquals(
			$this->tidy(file_get_contents(
				__DIR__
				. DIRECTORY_SEPARATOR . 'Support'
				. DIRECTORY_SEPARATOR . 'Indentation.html'
			)),
			$this->tidy($parsedText)
		);
	}
}