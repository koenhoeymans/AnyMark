<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Parser;

use AnyMark\Events\ParsingPatternMatch;
use Epa\Pluggable;
use Epa\Observable;
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
	use Pluggable;

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
		$parentNode = $text->getParent();
		$subpatterns = $this->patternTree->getSubpatterns($parentPattern);

		$patternMatches = new \SplObjectStorage();
		$textComponentsToParse = array($text);

		foreach($subpatterns as $subpattern)
		{
			$newTextComponentsToParse = array();
			foreach ($textComponentsToParse as $textComponentToParse)
			{
				$newTextComponentsToParse[] = $textComponentToParse;
				while (($match = $this->applyPattern($textComponentToParse, $subpattern, $parentPattern)) !== array())
				{
					$parentNode->replace(
						$match['textComponentBeforeMatch'], $textComponentToParse
					);
					$parentNode->insertAfter(
						$match['match'], $match['textComponentBeforeMatch']
					);
					$parentNode->insertAfter(
						$match['textComponentAfterMatch'], $match['match']
					);

					$patternMatches->attach($match['match'], $subpattern);
					array_pop($newTextComponentsToParse); // was previous textComponentAfterMatch
					$newTextComponentsToParse[] = $match['textComponentBeforeMatch'];
					$newTextComponentsToParse[] = $match['textComponentAfterMatch'];
					$textComponentToParse = $match['textComponentAfterMatch'];
				}
			}
			$textComponentsToParse = $newTextComponentsToParse;
		}

		$this->handleMatches($patternMatches);
	}

	private function handleMatches(\SplObjectStorage $patternMatches)
	{
		foreach ($patternMatches as $patternMatch)
		{
			$pattern = $patternMatches->offsetGet($patternMatch);
			$query = $this->elementTree->createQuery($patternMatch);
			$createdText = $query->find($query->allText($query->withParentElement()));
			foreach ($createdText as $text)
			{
				$this->applyPatterns($text, $pattern);
			}
		}
	}

	private function applyPattern(
		Text $text, Pattern $pattern, Pattern $parentPattern = null, $offset = 0
	) {
		$parentElement = ($text->getParent() === $this->elementTree)
			? null
			: $text->getParent($text);
		$textToReplace = $text->getValue();

		if (!preg_match($pattern->getRegex(), $textToReplace, $match, PREG_OFFSET_CAPTURE, $offset))
		{
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
			'textComponentBeforeMatch' => $textBeforeMatch,
			'match' => $match,
			'textComponentAfterMatch' => $textFollowingMatch
		);
	}
}