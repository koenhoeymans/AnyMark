<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Parser;

use AnyMark\Events\ParsingPatternMatch;
use Epa\Api\ObserverStore;
use Epa\Api\Observable;
use AnyMark\Pattern\Pattern;
use AnyMark\Pattern\PatternTree;
use ElementTree\ElementTree;
use ElementTree\Component;
use ElementTree\Text;


/**
 * @package vidola
 */
class GlobalMatchRecursiveReplacer implements Parser, Observable
{
	use ObserverStore;

	private $patternTree;

	private $elementTree = null;

	public function __construct(PatternTree $patternTree)
	{
		$this->patternTree = $patternTree;
	}

	/**
	 * @see AnyMark\Parser.Parser::parse()
	 * @return \ElementTree\ElementTree
	 */
	public function parse($text)
	{
		$this->elementTree = new \ElementTree\ElementTree();
		$text = $this->elementTree->createText($text);
		$this->elementTree->append($text);
		$this->applyPatterns($text);

		return $this->elementTree;
	}

	private function applyPatterns(Text $text, Pattern $parentPattern = null)
	{
		$toParse = array($text);

		foreach($this->patternTree->getSubpatterns($parentPattern) as $subpattern)
		{
			foreach ($toParse as $textComponentToParse)
			{
				while (($match = $this->applyPattern($textComponentToParse, $subpattern, $parentPattern)) !== array())
				{
					$toParse = $this->updateTextComponentsToParse($toParse, $match);
					$this->updateElementTree($match);
					$this->handleMatch($match['match'], $subpattern);
					$textComponentToParse = $match['textComponentAfterMatch'];
				}
			}
		}
	}

	private function updateTextComponentsToParse(array $toParse, array $match)
	{
		$replacements = array();
		if ($match['textComponentBeforeMatch']->toString() !== '')
		{
			$replacements[] = $match['textComponentBeforeMatch'];
		}
		if ($match['textComponentAfterMatch']->toString() !== '')
		{
			$replacements[] = $match['textComponentAfterMatch'];
		}
		$key = array_search($match['matched'], $toParse, true);
		array_splice($toParse, $key, 1, $replacements);

		return $toParse;
	}

	private function updateElementTree(array $match)
	{
		$parentNode = $match['matched']->getParent();
		$parentNode->replace($match['match'], $match['matched']);
		if ($match['textComponentBeforeMatch']->toString() !== '')
		{
			$parentNode->insertBefore(
				$match['textComponentBeforeMatch'], $match['match']
			);
		}
		if ($match['textComponentAfterMatch']->toString() !== '')
		{
			$parentNode->insertAfter(
				$match['textComponentAfterMatch'], $match['match']
			);
		}
	}

	private function handleMatch(Component $patternMatch, Pattern $pattern)
	{
		if ($patternMatch->toString() === '')
		{
			return;
		}

		$query = $this->elementTree->createQuery($patternMatch);
		$createdText = $query->find($query->allText($query->withParentElement()));
		foreach ($createdText as $text)
		{
			$this->applyPatterns($text, $pattern);
		}
	}

	private function applyPattern(
		Text $text, Pattern $pattern, Pattern $parentPattern = null, $offset = 0
	) {
		$parentElement = ($text->getParent() === $this->elementTree)
			? null
			: $text->getParent($text);
		$textToReplace = $text->getValue();

		if (!preg_match(
			$pattern->getRegex(), $textToReplace, $match, PREG_OFFSET_CAPTURE, $offset
		)) {
			return array();
		}

		$matchOffset = $match[0][1];
		$matchLength = strlen($match[0][0]);
		foreach ($match as $key => $capture)
		{
			$match[$key] = $capture[0];
		}

		$textBeforeMatch = substr($textToReplace, 0, $matchOffset);
		$textBeforeMatch = $this->elementTree->createText($textBeforeMatch);

		$match = $pattern->handleMatch($match, $parentElement, $parentPattern);

		# if pattern decides there's no match after examining regex match
		# we can continue
		if (!$match)
		{
			return $this->applyPattern(
				$text, $pattern, $parentPattern, $matchOffset + $matchLength
			);
		}

		$this->notify(new ParsingPatternMatch($match, $pattern));

		$textFollowingMatch = substr($textToReplace, $matchOffset + $matchLength);
		$textFollowingMatch = $this->elementTree->createText($textFollowingMatch);

		return array(
			'matched' => $text,
			'textComponentBeforeMatch' => $textBeforeMatch,
			'match' => $match,
			'textComponentAfterMatch' => $textFollowingMatch
		);
	}
}