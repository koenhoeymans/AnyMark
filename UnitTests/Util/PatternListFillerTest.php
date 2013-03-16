<?php

use AnyMark\Pattern\Patterns\Strong;

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
	public function canFillPatternListFromFile()
	{
		$patternListMock = $this->getMock('AnyMark\\Pattern\\PatternList');
		$patternListMock
			->expects($this->at(0))
			->method('addPattern')
			->with(new \AnyMark\UnitTests\Support\DummyPattern());
		$patternListMock
			->expects($this->at(1))
			->method('addPattern')
			->with(
				new \AnyMark\UnitTests\Support\DummyPattern(),
				new \AnyMark\UnitTests\Support\DummyPattern()
			);
		$dummyJson = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'CustomPatterns.php';

		$this->filler->fill($patternListMock, $dummyJson);
	}

	/**
	 * @test
	 */
	public function canContainRecursiveNesting()
	{
		$patternList = new \AnyMark\Pattern\PatternList();
		$circularJson = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'CircularPatterns.php';

		$this->filler->fill($patternList, $circularJson);
	}

	/**
	 * @test
	 */
	public function canContainAliasForSubpatterns()
	{
		$patternListMock = $this->getMock('AnyMark\\Pattern\\PatternList');
		$patternListMock
			->expects($this->at(0))
			->method('addPattern')
			->with(new \AnyMark\Pattern\Patterns\Paragraph());
		$patternListMock
			->expects($this->at(1))
			->method('addPattern')
			->with(
				new \AnyMark\Pattern\Patterns\Strong(),
				new \AnyMark\Pattern\Patterns\Paragraph()
			);
		$patternListMock
			->expects($this->at(2))
			->method('addPattern')
			->with(
				new \AnyMark\Pattern\Patterns\Italic(),
				new \AnyMark\Pattern\Patterns\Paragraph()
			);
		$dummyJson = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'AliasSubpattern.php';

		$this->filler->fill($patternListMock, $dummyJson);
	}

	/**
	 * @test
	 */
	public function canContainAliasForParentPatterns()
	{
		$patternListMock = $this->getMock('AnyMark\\Pattern\\PatternList');
		$patternListMock
			->expects($this->at(0))
			->method('addPattern')
			->with(new \AnyMark\Pattern\Patterns\Strong());
		$patternListMock
			->expects($this->at(1))
			->method('addPattern')
			->with(new \AnyMark\Pattern\Patterns\Italic());
		$patternListMock
			->expects($this->at(2))
			->method('addPattern')
			->with(
				new \AnyMark\Pattern\Patterns\Emphasis(),
				new \AnyMark\Pattern\Patterns\Strong()
			);
		$patternListMock
			->expects($this->at(3))
			->method('addPattern')
			->with(
				new \AnyMark\Pattern\Patterns\Emphasis(),
				new \AnyMark\Pattern\Patterns\Italic()
			);

		$dummyJson = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'AliasParentPattern.php';

		$this->filler->fill($patternListMock, $dummyJson);
	}

	/**
	 * @test
	 */
	public function canContainAliasWithinAlias()
	{
		$patternListMock = $this->getMock('AnyMark\\Pattern\\PatternList');
		$patternListMock
			->expects($this->at(0))
			->method('addPattern')
			->with(new \AnyMark\Pattern\Patterns\Strong());
		$patternListMock
			->expects($this->at(1))
			->method('addPattern')
			->with(
				new \AnyMark\Pattern\Patterns\Strong(),
				new \AnyMark\Pattern\Patterns\Strong()
			);

		$dummyJson = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'AliasedAlias.php';

		$this->filler->fill($patternListMock, $dummyJson);
	}
}