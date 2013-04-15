<?php

use AnyMark\UnitTests\Support\MockPattern;

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Pattern_FileArrayPatternConfigTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$file = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'SimpleConfig.php';
		$this->config = new \AnyMark\Pattern\FileArrayPatternConfig($file);
	}

	/**
	 * @test
	 */
	public function returnsSpecifiedImplementationAsClassNameFromConfig()
	{
		$this->assertEquals(
			'\\AnyMark\\Pattern\\Patterns\\Italic',
			$this->config->getSpecifiedImplementation('italic')
		);
	}

	/**
	 * @test
	 */
	public function returnsNullWhenNoImplementationSpecified()
	{
		$this->assertEquals(
			null, $this->config->getSpecifiedImplementation('_ita_lic_')
		);
	}

	/**
	 * @test
	 */
	public function returnsAlias()
	{
		$this->assertEquals(array('strong'), $this->config->getAliased('foo'));
	}

	/**
	 * @test
	 */
	public function returnsEmptyListIfNoSubnames()
	{
		$this->assertEquals(array(), $this->config->getSubnames('_ita_lic_'));
	}

	/**
	 * @test
	 */
	public function returnsListWithSubnames()
	{
		$this->assertEquals(array('strong'), $this->config->getSubnames('italic'));
	}

	/**
	 * @test
	 */
	public function returnsSpecifiedObjectImplementationIfAddedByApi()
	{
		$pattern = new \AnyMark\UnitTests\Support\DummyPattern();

		$this->config->add('pattern', $pattern)->to('root');

		$this->assertEquals(
			$pattern, $this->config->getSpecifiedImplementation('pattern')
		);
	}

	/**
	 * @test
	 */
	public function returnsSpecifiedClassImplementationIfAddedByApi()
	{
		$this->config->add('pattern', 'class')->to('root');

		$this->assertEquals(
			'class', $this->config->getSpecifiedImplementation('pattern')
		);
	}

	/**
	 * @test
	 */
	public function canAddPatternAsLastSubpattern()
	{
		$this->config->add('mock')->to('root')->last();

		$names = $this->config->getSubnames('root');
		$this->assertEquals('mock', end($names));
	}

	/**
	 * @test
	 */
	public function canAddPatternAsFirstSubpattern()
	{
		$this->config->add('mock')->to('root')->first();

		$names = $this->config->getSubnames('root');
		$this->assertEquals('mock', array_shift($names));
	}
	
	/**
	 * @test
	 */
	public function patternCanBeAddedAfterOtherPattern()
	{
		$this->config->add('mock')->to('root')->after('italic');

		$this->assertEquals(
			array('italic', 'mock', 'foo'), $this->config->getSubnames('root')
		);
	}

	/**
	 * @test
	 */
	public function patternCanBeAddedBeforeOtherPattern()
	{
		$this->config->add('mock')->to('root')->before('foo');

		$this->assertEquals(
			array('italic', 'mock', 'foo'), $this->config->getSubnames('root')
		);
	}
}