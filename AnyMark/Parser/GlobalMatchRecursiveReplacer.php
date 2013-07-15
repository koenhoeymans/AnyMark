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
		$text = $document->createText($text);

		$document->append($text);

		$this->applyPatterns($text);

		return $document;
	}

	private function applyPatterns(Text $text, Pattern $parentPattern = null)
	{
		$parentElement = $text->getParent($text) ?: $text->getOwnerTree();
		$textToReplace = $text->getValue();
		$totalBytes = strlen($textToReplace);
		$currentByteOffset = 0;
		$patterns = $this->patternTree->getSubpatterns($parentPattern);

		foreach($patterns as $pattern)
		{
			$regex = $pattern->getRegex();
			if (!preg_match($regex, $textToReplace, $match, PREG_OFFSET_CAPTURE))
			{
				continue;
			}

			$matchOffset = $match[0][1];
			$matchLength = strlen($match[0][0]);
			foreach ($match as $key => $capture)
			{
				$match[$key] = $capture[0];
			}

			# create dom node from match
			$patternCreatedElement = $pattern->handleMatch(
				$match, $parentElement, $parentPattern
			);

			# if pattern decides there's no match after examining regex match
			# we can continue
			if (!$patternCreatedElement)
			{
				continue;
			}

			$this->notify(new ParsingPatternMatch($patternCreatedElement, $pattern));

			# add text node from text before match
			$textBeforeMatch = substr($textToReplace, 0, $matchOffset);
			$textBeforeMatch = $parentElement->createText($textBeforeMatch);
			$parentElement->replace($textBeforeMatch, $text);
			$this->applyPatterns($textBeforeMatch, $parentPattern);

			# applying subpatterns to node from match
			$parentElement->append($patternCreatedElement);
			$this->applySubpatterns($patternCreatedElement, $pattern);

			# create text node from text following match
			$textFollowingMatch = substr($textToReplace, $matchOffset + $matchLength);
			$textFollowingMatch = $parentElement->createText($textFollowingMatch);
			$parentElement->append($textFollowingMatch);
			$this->applyPatterns($textFollowingMatch, $parentPattern);

			return;
		}
	}

	private function applySubpatterns(Component $elementTree, Pattern $parentPattern)
	{
		$query = $elementTree->createQuery();
		$matches = $query->find($query->allText());

		foreach ($matches as $match)
		{
			$this->applyPatterns($match, $parentPattern);
		}
	}
}