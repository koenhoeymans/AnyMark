<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Pattern_PatternListTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->patternList = new \AnyMark\Pattern\PatternList();
	}

	/**
	 * @test
	 */
	public function keepsListOfAllSpecifiedPatterns()
	{
		// given
		$patternA = new \AnyMark\UnitTests\Support\MockPattern('@x@', 'a', 'b');
		$patternB = new \AnyMark\UnitTests\Support\MockPattern('@x@', 'a', 'b');

		// when
		$this->patternList->addPattern($patternA);
		$this->patternList->addPattern($patternB);

		// then
		$this->assertEquals(
			array($patternA, $patternB),
			$this->patternList->getPatterns()
		);
	}

	/**
	 * @test
	 */
	public function keepsListOfSpecifiedSubpatterns()
	{
		// given
		$patternA = new \AnyMark\UnitTests\Support\MockPattern('@x@', 'a', 'b');
		$patternB = new \AnyMark\UnitTests\Support\MockPattern('@x@', 'a', 'b');
		$patternC = new \AnyMark\UnitTests\Support\MockPattern('@x@', 'a', 'b');

		// when
		$this->patternList->addPattern($patternA);
		$this->patternList->addPattern($patternB, $patternA);
		$this->patternList->addPattern($patternC, $patternA);

		// then
		$this->assertEquals(
			array($patternB, $patternC),
			$this->patternList->getSubpatterns($patternA)
		);
	}
}