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
	 * @param Pattern $pattern
	 * 
	 * @return PatternList
	 */
	public function addRootPattern(Pattern $pattern)
	{
		$this->patterns[] = $pattern;

		return $this;
	}

	/**
	 * @param Pattern $subpattern
	 * @param Pattern $pattern
	 * 
	 * @return PatternList
	 */
	public function addSubpattern(Pattern $subpattern, Pattern $parentPattern)
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