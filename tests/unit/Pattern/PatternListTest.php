<?php

namespace Anymark\Pattern;

class PatternListTest extends \PHPUnit_Framework_TestCase
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
		$pattern = new \AnyMark\DummyPattern();

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
		$pattern = new \AnyMark\DummyPattern();

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
		$emphasis = new \AnyMark\Pattern\Patterns\Emphasis();
		$strong = new \AnyMark\Pattern\Patterns\Strong();

		$this->config
			->expects($this->atLeastOnce())
			->method('getSubnames')
			->will($this->returnValueMap(array(
				array('root', array('emphasis')),
				array('emphasis', array('strong')),
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
				array('emphasis', $emphasis)
			)));

		$this->assertEquals(array($strong), $this->patternList->getSubpatterns($emphasis));
	}

	/**
	 * @test
	 */
	public function dealiasesAliases()
	{
		$dummy = new \AnyMark\DummyPattern();
		$emphasis = new \AnyMark\Pattern\Patterns\Emphasis();
		$strong = new \AnyMark\Pattern\Patterns\Strong();

		$this->config
			->expects($this->atLeastOnce())
			->method('getSubnames')
			->will($this->returnValueMap(array(
				array('root', array('alias', 'dummy')),
				array('alias', array('dummy', 'alias')),
				array('dummy', array()),
				array('strong', array()),
				array('emphasis', array())
			)));
		$this->config
			->expects($this->atLeastOnce())
			->method('getAliased')
			->will($this->returnValueMap(array(
				array('alias', array('emphasis', 'strong')),
				array('dummy', array()),
				array('strong', array()),
				array('emphasis', array()),
			)));
		$this->config
			->expects($this->atLeastOnce())
			->method('getSpecifiedImplementation')
			->will($this->returnValueMap(array(
				array('alias', null),
				array('dummy', $dummy),
				array('emphasis', $emphasis),
				array('strong', $strong)
			)));

		$this->assertEquals(
			array($emphasis, $strong, $dummy), $this->patternList->getSubpatterns()
		);
	}

	/**
	 * @test
	 */
	public function treeCanBeCircular()
	{
		$emphasis = new \AnyMark\Pattern\Patterns\Emphasis();
		$strong = new \AnyMark\Pattern\Patterns\Strong();

		$this->config
			->expects($this->atLeastOnce())
			->method('getSubnames')
			->will($this->returnValueMap(array(
				array('root', array('emphasis')),
				array('emphasis', array('strong')),
				array('strong', array('emphasis'))
			)));
		$this->config
			->expects($this->any())
			->method('getAliased')
			->will($this->returnValue(array()));
		$this->config
			->expects($this->atLeastOnce())
			->method('getSpecifiedImplementation')
			->will($this->returnValueMap(array(
				array('emphasis', $emphasis),
				array('strong', $strong),
				array('alias', null)
			)));

		$this->assertEquals(array($emphasis), $this->patternList->getSubpatterns());
		$this->assertEquals(array($emphasis), $this->patternList->getSubpatterns($strong));
	}

	/**
	 * @test
	 */
	public function instancesAreReused()
	{
		$pattern = new \AnyMark\DummyPattern();

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