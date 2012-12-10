<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Util;

use AnyMark\Pattern\PatternList;
use Fjor\Fjor;

/**
 * @package AnyMark
 */
class PatternListFiller
{
	private $objectGraphConstructor;

	private $doneCombinations = array();

	public function __construct(Fjor $objectGraphConstructor)
	{
		$this->objectGraphConstructor = $objectGraphConstructor;
	}

	/**
	 * Fill PatternList based on patterns in config (ini) file.
	 * 
	 * @param PatternList $patternList
	 * @param string $iniFile
	 * 
	 * @return PatternList
	 */
	public function fill(PatternList $patternList, $iniFile)
	{
		$patternTree = parse_ini_file($iniFile);

		foreach ($patternTree['root'] as $pattern)
		{
			$patternList->addRootPattern($this->getPattern($pattern));
			$this->addSubpatterns($pattern, $patternList, $patternTree);
		}

		$this->doneCombinations = array();

		return $patternTree;
	}

	private function addSubpatterns($pattern, PatternList $patternList, array $patternTree)
	{
		if (!isset($patternTree[$pattern]))
		{
			return;
		}

		foreach ($patternTree[$pattern] as $subpattern)
		{
			if (in_array(array($pattern, $subpattern), $this->doneCombinations))
			{
				continue;
			}

			$this->doneCombinations[] = array($pattern, $subpattern);

			$patternList->addSubpattern(
				$this->getPattern($subpattern),
				$this->getPattern($pattern)
			);

			if ($pattern != $subpattern)
			{
				$this->addSubpatterns($subpattern, $patternList, $patternTree);
			}
		}
	}

	private function getPattern($name)
	{
		if (!class_exists($name))
		{
			$name = 'AnyMark\\Pattern\\Patterns\\' . ucfirst($name);
		}

		return $this->objectGraphConstructor->get($name);
	}
}