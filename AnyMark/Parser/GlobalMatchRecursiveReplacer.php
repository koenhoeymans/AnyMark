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
$tree = new \ElementTree\ElementTree();
$div = $tree->createElement('div');
$a = $tree->createElement('a');
$txt = $tree->createText('foo');
$b = $tree->createElement('a');
$tree->replace($a, $div);
$tree->append($b, $a);
$tree->append($txt, $b);
var_dump($tree->toString());
die();
		$parentElement = $text->getParent() ?: $text->getOwnerTree();
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
					$parentElement->replace($match[0], $text);
//var_dump('replacing: ' . $text->toString() . ' with: ' . $match[0]->toString());
					$parentElement->append($match[2], $match[0]);
					$parentElement->append($match[1], $match[0]);
// var_dump('adding: ' . $match[1]->toString());
// var_dump('so parent is now: ' . $parentElement->toString());
					//$parentElement->append($match[2], $match[1]);
//var_dump('finally: ' . $parentElement->toString());
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
		$parentElement = $text->getParent($text) ?: $text->getOwnerTree();
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
		$textBeforeMatch = $parentElement->createText($textBeforeMatch);

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
		$textFollowingMatch = $parentElement->createText($textFollowingMatch);

		return array($textBeforeMatch, $match, $textFollowingMatch);
	}
}