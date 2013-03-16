<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern;

/**
 * @package vidola
 * 
 * List of patterns with its subpatterns.
 */
class PatternList
{
	private $patterns = array();

	private $subpatterns = array();

	/**
	 * @return array
	 */
	public function getPatterns()
	{
		return $this->patterns;
	}

	/**
	 * @param Pattern $pattern
	 * @return array
	 */
	public function getSubpatterns(Pattern $pattern)
	{
		foreach ($this->subpatterns as $patternArr)
		{
			if ($patternArr['pattern'] == $pattern)
			{
				return $patternArr['subpatterns'];
			}
		}

		return array();
	}

	/**
	 * Add a pattern to the list. If a parent is specified the pattern will
	 * be a subpattern of the parent.
	 * 
	 * @param Pattern $pattern
	 * @param Pattern $parentPattern
	 */
	public function addPattern(Pattern $pattern, Pattern $parentPattern = null)
	{
		if (!$parentPattern)
		{
			$this->addRootPattern($pattern);
		}
		else
		{
			$this->addSubpattern($pattern, $parentPattern);
		}
	}

	private function addRootPattern(Pattern $pattern)
	{
		$this->patterns[] = $pattern;

		return $this;
	}

	private function addSubpattern(Pattern $subpattern, Pattern $parentPattern)
	{
		foreach ($this->subpatterns as &$patternArr)
		{
			if ($patternArr['pattern'] == $parentPattern)
			{
				$patternArr['subpatterns'][] = $subpattern;
				return $this;
			}
		}

		$this->subpatterns[] = array(
			'pattern' => $parentPattern, 'subpatterns' => array($subpattern)
		);

		return $this;
	}
}