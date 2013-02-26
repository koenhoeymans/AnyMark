<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Util_PatternListFillerTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->filler = new \AnyMark\Util\PatternListFiller(
			new \Fjor\Dsl\Dsl(new \Fjor\ObjectFactory\GenericObjectFactory())
		);
	}

	/**
	 * @test
	 */
	public function canFillPatternListFromIni()
	{
		$patternListMock = $this->getMock('AnyMark\\Pattern\\PatternList');
		$patternListMock
			->expects($this->once())
			->method('addRootPattern')
			->with(new \AnyMark\Pattern\Patterns\Emphasis());
		$patternListMock
			->expects($this->once())
			->method('addSubPattern')
			->with(new \AnyMark\Pattern\Patterns\Italic(), new \AnyMark\Pattern\Patterns\Emphasis());
		$dummyIni = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'Dummy.ini';

		$this->filler->iniFill($patternListMock, $dummyIni);
	}

	/**
	 * @test
	 */
	public function handlesCircularDependencies()
	{
		$patternList = new \AnyMark\Pattern\PatternList();
		$circularIni = __DIR__
				. DIRECTORY_SEPARATOR . '..'
				. DIRECTORY_SEPARATOR . 'Support'
				. DIRECTORY_SEPARATOR . 'Circular.ini';

		// shouldn't throw maximum nesting level error
		$this->filler->iniFill($patternList, $circularIni);
	}

	/**
	 * @test
	 */
	public function fullyQualifiedPatternNamesArePossible()
	{
		$patternListMock = $this->getMock('AnyMark\\Pattern\\PatternList');
		$patternListMock
			->expects($this->once())
			->method('addRootPattern')
			->with(new \AnyMark\UnitTests\Support\DummyPattern());
		$dummyIni = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'Custom.ini';

		$this->filler->iniFill($patternListMock, $dummyIni);
	}
}