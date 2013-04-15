<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern;

# lots of recursion while adding patterns to tree
ini_set('xdebug.max_nesting_level', 200);

/**
 * @package AnyMark
 * 
 * List of patterns with its subpatterns.
 */
class PatternList implements PatternTree
{
	private $patternFactory;

	private $config;

	private $configCopy;

	private $tree;

	private $implementations = array();

	private $hasBeenAddedFromConfig = array();

	public function __construct(PatternConfig $config, PatternFactory $factory)
	{
		$this->config = $config;
		$this->patternFactory = $factory;
	}

	/**
	 * @see \AnyMark\Pattern\PatternTree::getSubpatterns()
	 */
	public function getSubpatterns(Pattern $parentPattern = null)
	{
 		$this->updateFromConfig();

		if (!$parentPattern)
		{
			return $this->tree['root'];
		}

		$patternId = $this->getName($parentPattern);
		return (isset($this->tree[$patternId]))
			? $this->tree[$patternId]
			: array();
	}

	private function updateFromConfig()
	{
		if ($this->config != $this->configCopy)
		{
			$this->update();
			$this->configCopy = clone $this->config;
		}
	}

	private function update()
	{
		$this->tree = array();
		$this->hasBeenAddedFromConfig = array();
		$this->addPatterns($this->config->getSubnames('root'));
	}

	private function addPatterns(array $patternNames, $parentName = null)
	{
		foreach ($patternNames as $patternName)
		{
			$this->addPattern($patternName, $parentName);
		}
	}

	private function addPattern($patternName, $parentName = null)
	{
		if (in_array(array($patternName, $parentName), $this->hasBeenAddedFromConfig))
		{
			return;
		}
		$this->hasBeenAddedFromConfig[] = array($patternName, $parentName);

		foreach ($this->getDealiasedNames($patternName) as $dealiasedPatternName)
		{
			$this->addDealiasedPatternName($dealiasedPatternName, $parentName);
			$this->addPatterns(
				$this->config->getSubnames($patternName), $dealiasedPatternName
			);
			$this->addPatterns(
				$this->config->getSubnames($dealiasedPatternName), $dealiasedPatternName
			);
		}
	}

	private function addDealiasedPatternName($patternName, $parentName = null)
	{
		$parentId = $parentName ?: 'root';
		$this->tree[$parentId][] = $this->getPattern($patternName);
	}

	private function isAlias($name)
	{
		return $this->config->getAliased($name) !== array();
	}

	private function getDealiasedNames($alias)
	{
		if (!$this->isAlias($alias))
		{
			return array($alias);
		}

		$aliasedNames = $this->config->getAliased($alias);
		$dealiasedNames = array();
		# alias can contain other alias
		foreach ($aliasedNames as $aliasedName)
		{
			if ($this->isAlias($aliasedName))
			{
				$dealiasedNames = array_merge(
					$dealiasedNames, $this->getDealiasedNames($aliasedName)
				);
			}
			else
			{
				$dealiasedNames[] = $aliasedName;
			}
		}

		return array_unique($dealiasedNames);
	}

	private function getPattern($name)
	{
		if (isset($this->implementations[$name]))
		{
			return $this->implementations[$name];
		}

		# implementation defined
		if ($impl = $this->config->getSpecifiedImplementation($name))
		{
			if (!is_object($impl))
			{
				$impl = $this->patternFactory->create($impl);
			}
		}
		# or name is implementation
		else
		{
			$impl = $this->patternFactory->create($name);
		}

		$this->implementations[$name] = $impl;

		return $impl;
	}

	private function getName(Pattern $pattern)
	{
		$class = get_class($pattern);
		foreach ($this->implementations as $name => $implementation)
		{
			if ($pattern == $implementation)
			{
				return $name;
			}
		}
	}
}