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
		$anyMark = \AnyMark\AnyMark::createWith(
			\AnyMark\AnyMark::defaultWiring($patternsFile)
		);

		// when
		$parsedText = trim($anyMark->parse('foo foo')->toString());

		// then
		$this->assertEquals(
			'bar bar', $parsedText
		);
	}

	/**
	 * @test
	 */
	public function canAddPatterns()
	{
		$this->markTestIncomplete();
	}
}