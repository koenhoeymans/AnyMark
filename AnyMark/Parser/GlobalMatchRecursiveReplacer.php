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

	private $ownerTree = null;

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
		$document = new \ElementTree\ElementTree();
		$this->ownerTree = $document;
		$text = $document->createText($text);

		$document->append($text);

		$this->applyPatterns($text);

		return $document;
	}

	private function applyPatterns(Text $text, Pattern $parentPattern = null)
	{
		$parentNode = $text->getParent() ?: $this->ownerTree;
		$subpatterns = $this->patternTree->getSubpatterns($parentPattern);

		$patternMatches = new \SplObjectStorage();
		$textLeft = array($text);

		foreach($subpatterns as $subpattern)
		{
			$moreTextLeft = array();
			foreach ($textLeft as $text)
			{
				$moreTextLeft[] = $text;
				while (($match = $this->applyPattern($text, $subpattern, $parentPattern)) !== array())
				{
					$parentNode->replace($match[0], $text);
					$parentNode->append($match[2], $match[0]);
					$parentNode->append($match[1], $match[0]);
					$text = $match[2];
					$patternMatches->attach($match[1], $subpattern);
					array_pop($moreTextLeft); // previous $match[2] text
					$moreTextLeft[] = $match[0];
					$moreTextLeft[] = $match[2];
				}
			}
			$textLeft = $moreTextLeft;
		}

		foreach ($patternMatches as $patternMatch)
		{
			$pattern = $patternMatches->offsetGet($patternMatch);
			$query = $patternMatch->createQuery();
			$createdText = $query->find($query->allText());
			foreach ($createdText as $text)
			{
				$this->applyPatterns($text, $pattern);
			}
		}
	}

	private function applyPattern(
		Text $text, Pattern $pattern, Pattern $parentPattern = null, $offset = 0
	) {
		$parentElement = $text->getParent($text) ?: $this->ownerTree;
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
		$textBeforeMatch = $this->ownerTree->createText($textBeforeMatch);

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
		$textFollowingMatch = $this->ownerTree->createText($textFollowingMatch);

		return array($textBeforeMatch, $match, $textFollowingMatch);
	}
}