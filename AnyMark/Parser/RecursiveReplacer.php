<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Parser;

use AnyMark\Pattern\Pattern;
use AnyMark\Pattern\PatternList;
use AnyMark\ComponentTree\Component;
use AnyMark\ComponentTree\Text;


/**
 * @package vidola
 */
class RecursiveReplacer implements Parser
{
	private $patternList;

	public function __construct(PatternList $patternList)
	{
		$this->patternList = $patternList;
	}

	/**
	 * @see AnyMark\Parser.Parser::parse()
	 * @return \DomDocument
	 */
	public function parse($text)
	{
		$document = new \AnyMark\ComponentTree\ComponentTree();
		$text = $document->createText($text);
		$document->append($text);

		$this->applyPatterns($text);

		$document->query(function ($a) { $this->restoreEscaped($a); });

		return $document;
	}

	private function applyPatterns(Text $text, Pattern $parentPattern = null)
	{
		$parentElement = $text->getParent($text);
		$textToReplace = $text->getValue();
		$totalBytes = strlen($textToReplace);
		$currentByteOffset = 0;
		$endOfTextReached = false;
		$patterns = ($parentPattern == null) ?
			$this->patternList->getPatterns() :
			$this->patternList->getSubpatterns($parentPattern);

		while (!$endOfTextReached)
		{
			foreach($patterns as $pattern)
			{
				$regex = $pattern->getRegex();
				preg_match($regex . 'A', $textToReplace, $match, 0, $currentByteOffset);

				if (!empty($match))
				{
					# create dom node from match
					$patternCreatedElement = $pattern->handleMatch($match, $parentElement, $parentPattern);

					# if pattern decides there's no match after examining regex match
					# we can continue
					if (!$patternCreatedElement)
					{
						continue;
					}

					# add text node from text before match
					$textBeforeMatch = substr($textToReplace, 0, $currentByteOffset);
					$parentElement->replace(
						$parentElement->createText($textBeforeMatch), $text
					);

					# applying subpatterns to dom node from match
					$parentElement->append($patternCreatedElement);
					$this->applySubpatterns($patternCreatedElement, $pattern);

					# create text node from text following match
					$textFollowingMatch = substr(
						$textToReplace, strlen($match[0]) + $currentByteOffset
					);
					$textFollowingMatch = $parentElement->createText(
						$textFollowingMatch
					);
					$parentElement->append($textFollowingMatch);
					$this->applyPatterns($textFollowingMatch, $parentPattern);

					return;
				}
			}

			if ($currentByteOffset == $totalBytes)
			{
				$endOfTextReached = true;
			}
			else
			{
				$currentByteOffset++;
			}
		}
	}

	private function applySubpatterns(Component $elementTree, Pattern $parentPattern)
	{
		foreach ($elementTree->getChildren() as $childNode)
		{
			if ($childNode instanceof \AnyMark\ComponentTree\Text)
			{
				$this->applyPatterns($childNode, $parentPattern);
			}
			else
			{
				$this->applySubpatterns($childNode, $parentPattern);
			}
		}
	}

	private function restoreEscaped(Component $component)
	{
		if (!($component instanceof \AnyMark\ComponentTree\Text))
		{
			return;
		}
		$parent = $component->getParent();
		if (!$parent || !($parent instanceof \AnyMark\ComponentTree\Element))
		{
			return;
		}
		if ($parent->getName() !== 'code')
		{
			# don't need the backslash for escaped characters anymore
			$component->setValue(preg_replace(
				'@\\\\([^ ])@', "\${1}", $component->getValue()
			));
		}
	}
}