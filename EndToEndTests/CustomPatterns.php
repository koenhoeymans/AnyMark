<?php

require_once('TestHelper.php');

class AnyMark_EndToEndTests_CustomPatternsTest extends \AnyMark\EndToEndTests\Support\Tidy
{
	/**
	 * @test
	 */
	public function customPatterns()
	{
		// given
		$anyMark = \AnyMark\AnyMark::setup()->get('AnyMark\\AnyMark');
		$anyMark->setPatternsIni(__DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'CustomPatterns.ini');
	
		// when
		$parsedText = $anyMark->saveXml($anyMark->parse('foo bar'));
	
		// then
		$this->assertEquals(
			'foo foo',
			$this->tidy($parsedText)
		);
	}
}