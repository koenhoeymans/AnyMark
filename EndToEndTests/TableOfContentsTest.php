<?php

require_once('TestHelper.php');

class AnyMark_EndToEndTests_TableOfContentsTest extends \AnyMark\EndToEndTests\Support\Tidy
{
	/**
	 * @test
	 */
	public function tableOfContents()
	{
		// given
		$anyMark = \AnyMark\AnyMark::setup()->get('AnyMark\\AnyMark');
		$text = file_get_contents(__DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'TableOfContents.txt');

		// when
		$parsedText = $anyMark->parse($text);

		// then
		$this->assertEquals(
			$this->tidy(file_get_contents(
				__DIR__
				. DIRECTORY_SEPARATOR . 'Support'
				. DIRECTORY_SEPARATOR . 'TableOfContents.html'
			)),
			$this->tidy($parsedText)
		);
	}
}