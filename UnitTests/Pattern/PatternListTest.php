<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Pattern_PatternListTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->config = $this->getMock('AnyMark\\Pattern\\PatternConfig');
		$this->factory = $this->getMock('AnyMark\\Pattern\\PatternFactory');
		$this->patternList = new \AnyMark\Pattern\PatternList(
			$this->config, $this->factory
		);
	}

	/**
	 * @test
	 */
	public function returnsSubpatternsOfRootByNotSpecifyingParent()
	{
		$pattern = new \AnyMark\UnitTests\Support\DummyPattern();

		$this->config
			->expects($this->atLeastOnce())
			->method('getSubnames')
			->will($this->returnValue(array('dummy')));
		$this->config
			->expects($this->atLeastOnce())
			->method('getAliased')
			->will($this->returnValue(array()));
		$this->config
			->expects($this->atLeastOnce())
			->method('getSpecifiedImplementation')
			->with('dummy')
			->will($this->returnValue('\\AnyMark\\UnitTests\\Support\\DummyPattern'));
		$this->factory
			->expects($this->atLeastOnce())
			->method('create')
			->with('\\AnyMark\\UnitTests\\Support\\DummyPattern')
			->will($this->returnValue($pattern));

		$this->assertEquals(array($pattern), $this->patternList->getSubpatterns());
	}

	/**
	 * @test
	 */
	public function usesClassNameAsImplementationIfNoImplementationSpecified()
	{
		$pattern = new \AnyMark\UnitTests\Support\DummyPattern();

		$this->config
			->expects($this->atLeastOnce())
			->method('getSubnames')
			->will($this->returnValue(array('\\AnyMark\\UnitTests\\Support\\DummyPattern')));
		$this->config
			->expects($this->atLeastOnce())
			->method('getAliased')
			->will($this->returnValue(array()));
		$this->config
			->expects($this->atLeastOnce())
			->method('getSpecifiedImplementation')
			->with('\\AnyMark\\UnitTests\\Support\\DummyPattern')
			->will($this->returnValue(null));
		$this->factory
			->expects($this->atLeastOnce())
			->method('create')
			->with('\\AnyMark\\UnitTests\\Support\\DummyPattern')
			->will($this->returnValue($pattern));

		$this->assertEquals(array($pattern), $this->patternList->getSubpatterns());
	}

	/**
	 * @test
	 */
	public function providesSubpatternsOfSpecificPattern()
	{
		$italic = new \AnyMark\Pattern\Patterns\Italic();
		$strong = new \AnyMark\Pattern\Patterns\Strong();

		$this->config
			->expects($this->atLeastOnce())
			->method('getSubnames')
			->will($this->returnValueMap(array(
				array('root', array('italic')),
				array('italic', array('strong')),
				array('strong', array())
			)));
		$this->config
			->expects($this->any())
			->method('getAliased')
			->will($this->returnValue(array()));
		$this->config
			->expects($this->atLeastOnce())
			->method('getSpecifiedImplementation')
			->will($this->returnValueMap(array(
				array('strong', $strong),
				array('italic', $italic)
			)));

		$this->assertEquals(array($strong), $this->patternList->getSubpatterns($italic));
	}

	/**
	 * @test
	 */
	public function dealiasesAliases()
	{
		$dummy = new \AnyMark\UnitTests\Support\DummyPattern();
		$italic = new \AnyMark\Pattern\Patterns\Italic();
		$strong = new \AnyMark\Pattern\Patterns\Strong();

		$this->config
			->expects($this->atLeastOnce())
			->method('getSubnames')
			->will($this->returnValueMap(array(
				array('root', array('alias', 'dummy')),
				array('alias', array('dummy', 'alias')),
				array('dummy', array()),
				array('strong', array()),
				array('italic', array())
			)));
		$this->config
			->expects($this->atLeastOnce())
			->method('getAliased')
			->will($this->returnValueMap(array(
				array('alias', array('italic', 'strong')),
				array('dummy', array()),
				array('strong', array()),
				array('italic', array()),
			)));
		$this->config
			->expects($this->atLeastOnce())
			->method('getSpecifiedImplementation')
			->will($this->returnValueMap(array(
				array('alias', null),
				array('dummy', $dummy),
				array('italic', $italic),
				array('strong', $strong)
			)));

		$this->assertEquals(
			array($italic, $strong, $dummy), $this->patternList->getSubpatterns()
		);
	}

	/**
	 * @test
	 */
	public function treeCanBeCircular()
	{
		$italic = new \AnyMark\Pattern\Patterns\Italic();
		$strong = new \AnyMark\Pattern\Patterns\Strong();

		$this->config
			->expects($this->atLeastOnce())
			->method('getSubnames')
			->will($this->returnValueMap(array(
				array('root', array('italic')),
				array('italic', array('strong')),
				array('strong', array('italic'))
			)));
		$this->config
			->expects($this->any())
			->method('getAliased')
			->will($this->returnValue(array()));
		$this->config
			->expects($this->atLeastOnce())
			->method('getSpecifiedImplementation')
			->will($this->returnValueMap(array(
				array('italic', $italic),
				array('strong', $strong),
				array('alias', null)
			)));

		$this->assertEquals(array($italic), $this->patternList->getSubpatterns());
		$this->assertEquals(array($italic), $this->patternList->getSubpatterns($strong));
	}

	/**
	 * @test
	 */
	public function instancesAreReused()
	{
		$pattern = new \AnyMark\UnitTests\Support\DummyPattern();

		$this->config
			->expects($this->atLeastOnce())
			->method('getSubnames')
			->will($this->returnValue(array('dummy')));
		$this->config
			->expects($this->any())
			->method('getAliased')
			->will($this->returnValue(array()));
		$this->config
			->expects($this->atLeastOnce())
			->method('getSpecifiedImplementation')
			->with('dummy')
			->will($this->returnValue('\\AnyMark\\UnitTests\\Support\\DummyPattern'));
		$this->factory
			->expects($this->atLeastOnce())
			->method('create')
			->with('\\AnyMark\\UnitTests\\Support\\DummyPattern')
			->will($this->returnValue($pattern));

		$this->assertSame(
			$this->patternList->getSubpatterns(), $this->patternList->getSubpatterns()
		);
	}
}