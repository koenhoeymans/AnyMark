<?php

/**
 * @package Anymark
 */
namespace AnyMark\Pattern;

use AnyMark\Pattern\PatternConfigDsl\Add;
use AnyMark\Pattern\PatternConfigDsl\To;
use AnyMark\Pattern\PatternConfigDsl\Where;

/**
 * @package AnyMark
 */
class FileArrayPatternConfig implements PatternConfig, Add, To, Where
{
	private $dsl = array(
		'patternName' => null,
		'parentPatternName' => null
	);

	public function __construct($file)
	{
		$this->config = require $file;
	}

	/**
	 * @see \AnyMark\Pattern\PatternConfig::getSpecifiedImplementation()
	 */
	public function getSpecifiedImplementation($name)
	{
		return isset($this->config['implementations'][$name])
			? $this->config['implementations'][$name]
			: null;
	}

	/**
	 * @see \AnyMark\Pattern\PatternConfig::getAliased()
	 */
	public function getAliased($alias)
	{
		return isset($this->config['alias'][$alias])
			? $this->config['alias'][$alias]
			: array();
	}

	/**
	 * @see \AnyMark\Pattern\PatternConfig::getSubnames()
	 */
	public function getSubnames($name)
	{
		return isset($this->config['tree'][$name])
			? $this->config['tree'][$name]
			: array();
	}

	/**
	 * @see \AnyMark\Pattern\PatternListDsl\Add::add()
	 */
	public function add($name, $implementation = null)
	{
		if (is_object($implementation))
		{
			$this->config['implementations'][$name] = $implementation;
		}
		elseif (is_string($implementation))
		{
			$this->config['implementations'][$name] = $implementation;
		}

 		$this->dsl['patternName'] = $name;

 		return $this;
	}

	/**
	 * @see \AnyMark\Pattern\PatternListDsl\To::to()
	 */
	public function to($parentPatternName)
	{
 		$this->dsl['parentPatternName'] = $parentPatternName;

 		return $this;
	}

	/**
	 * @see \AnyMark\Pattern\PatternListDsl\Where::last()
	 */
	public function last()
	{
		$name = $this->dsl['patternName'];
		$parentName = $this->dsl['parentPatternName'];
		$this->config['tree'][$parentName][] = $name;
	}

	/**
	 * @see \AnyMark\Pattern\PatternListDsl\Where::first()
	 */
	public function first()
	{
		$name = $this->dsl['patternName'];
		$parentName = $this->dsl['parentPatternName'];
		if (isset($this->config['tree'][$parentName]))
		{
			array_unshift($this->config['tree'][$parentName], $name);
		}
		else
		{
			$this->config['tree'][$parentName][] = $name;
		}
	}

	/**
	 * @see \AnyMark\Pattern\PatternListDsl\Where::after()
	 */
	public function after($patternName)
	{
		$name = $this->dsl['patternName'];
		$parentName = $this->dsl['parentPatternName'];
		$subpatterns = $this->config['tree'][$parentName];
		$position = array_search($patternName, $subpatterns);
		array_splice($subpatterns, $position+1, 0, $name);
		$this->config['tree'][$parentName] = $subpatterns;
	}

	/**
	 * @see \AnyMark\Pattern\PatternListDsl\Where::before()
	 */
	public function before($patternName)
	{
		$name = $this->dsl['patternName'];
		$parentName = $this->dsl['parentPatternName'];
		$subpatterns = $this->config['tree'][$parentName];
		$position = array_search($patternName, $subpatterns);
		array_splice($subpatterns, $position, 0, $name);
		$this->config['tree'][$parentName] = $subpatterns;
	}
}