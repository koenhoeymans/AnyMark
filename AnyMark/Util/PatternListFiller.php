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

	public function __construct(Fjor $objectGraphConstructor)
	{
		$this->objectGraphConstructor = $objectGraphConstructor;
	}

	public function fill(PatternList $patternList, $patternsFile)
	{
		$patterns = require $patternsFile;

		if (isset($patterns['alias']))
		{
			# removing all aliases and have a list of patterns and subpatterns only
			$aliases = $this->deAliasAliases((array) $patterns['alias']);
			$patterns = $this->deAliasPatterns((array) $patterns['patterns'], $aliases);
		}
		else
		{
			$patterns = $patterns['patterns'];
		}

		$this->addPatterns($patterns, $patternList);
	}

	private function addPatterns($patterns, PatternList $patternList)
	{
		foreach ($patterns as $parentPattern => $subpatterns)
		{
			foreach ($subpatterns as $subpattern)
			{
				$subpattern = $this->getPattern($subpattern);
				if ($parentPattern === 'root')
				{
					$patternList->addRootPattern($subpattern);
				}
				else
				{
					$patternList->addSubpattern(
						$subpattern, $this->getPattern($parentPattern)
					);
				}
			}
		}
	}

	# $alias => array('no', 'aliases', '(all replaced)')
	private function deAliasAliases(array $aliases)
	{
		foreach ($aliases as $alias => $substitutes)
		{
			$originals = array();
			foreach ($substitutes as $substitute)
			{
				$originals = array_merge(
					$originals, $this->getOriginalReferenced($substitute, $aliases)
				);
			}
			$aliases[$alias] = array_unique($originals);
		}

		return $aliases;
	}

	# if an alias references another alias we want a list of all original referenced
	private function getOriginalReferenced($name, $aliases)
	{
		if (!isset($aliases[$name]))
		{
			return array($name);
		}

		$originalReferenced = array();
		$referenced = $aliases[$name];
		foreach ($referenced as $ref)
		{
			$originalReferenced = array_merge(
				$originalReferenced, $this->getOriginalReferenced($ref, $aliases)
			);
		}

		return array_unique($originalReferenced);
	}

	# we know $aliases has been de-aliased, now it's time for the $patterns
	private function deAliasPatterns(array $patterns, array $aliases)
	{
		$patterns = $this->deAliasParentPatterns($patterns, $aliases);
		foreach ($patterns as $pattern => $subpatterns)
		{
			$patterns[$pattern] = $this->deAliasSubpatterns($subpatterns, $aliases);
		}

		return $patterns;
	}

	# replace aliases for patterns, don't care if there are aliases within subpatterns
	private function deAliasParentPatterns(array $patterns, array $aliases)
	{
		foreach ($patterns as $parentPattern => $subpatterns)
		{
			if (!isset($aliases[$parentPattern]))
			{
				continue;
			}

			$parentPatternAliases = $aliases[$parentPattern];
			foreach ($parentPatternAliases as $pattern)
			{
				$existing = isset($patterns[$pattern]) ? $patterns[$pattern] : array();
				$patterns[$pattern] = array_merge($existing, $subpatterns);
			}
			unset($patterns[$parentPattern]);
		}

		return $patterns;
	}

	# remove all aliases from subpatterns and replace them by non-aliased patterns
	private function deAliasSubpatterns(array $subpatterns, array $aliases)
	{
		$deAliasedSubpatterns = array();
		foreach ($subpatterns as $subpattern)
		{
			if (isset($aliases[$subpattern]))
			{
				$deAliasedSubpatterns = array_merge(
					$deAliasedSubpatterns, $aliases[$subpattern]
				);
			}
			else
			{
				$deAliasedSubpatterns[] = $subpattern;
			}
		}
		return $deAliasedSubpatterns;
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