<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Parser;

use AnyMark\Pattern\Pattern;
use AnyMark\Pattern\PatternTree;
use ElementTree\ElementTree;
use ElementTree\Component;
use ElementTree\Text;


/**
 * @package vidola
 */
class RecursiveReplacer implements Parser
{
	private $patternTree;

	public function __construct(PatternTree $patternTree)
	{
		$this->patternTree = $patternTree;
	}

	/**
	 * @see AnyMark\Parser.Parser::parse()
	 * @return \DomDocument
	 */
	public function parse($text)
	{
		$document = new \ElementTree\ElementTree();
		$text = $document->createText($text);

		$document->append($text);

		$this->applyPatterns($text);

		$this->restoreEscaped($document);

		return $document;
	}

	private function applyPatterns(Text $text, Pattern $parentPattern = null)
	{
		$parentElement = $text->getParent($text) ?: $text->getOwnerTree();
		$textToReplace = $text->getValue();
		$totalBytes = strlen($textToReplace);
		$currentByteOffset = 0;
		$endOfTextReached = false;
		$patterns = $this->patternTree->getSubpatterns($parentPattern);

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
		$ownerTree = $elementTree->getOwnerTree();
		$callback = function($text) use ($parentPattern)
		{
			$this->applyPatterns($text, $parentPattern);
		};
		$elementTree->query($ownerTree->createFilter($callback)->allText());
	}

	private function restoreEscaped(ElementTree $document)
	{
		$filter = $document->createFilter(function (Text $component)
		{
			# don't need the backslash for escaped characters anymore
			$component->setValue(preg_replace(
				'@\\\\([^ ])@', "\${1}", $component->getValue()
			));
		});

		$document->query(
			$filter->lAnd(
				$filter->allText(),
				$filter->hasParentElement(),
				$filter->not($filter->hasParentElement('code'))
			)
		);
	}
}