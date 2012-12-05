<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Parser;

use AnyMark\Pattern\Pattern;
use AnyMark\Pattern\PatternList;
use AnyMark\Processor\TextProcessor;
use AnyMark\Processor\DomProcessor;
use AnyMark\TextReplacer\TextReplacer;


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
		$domDoc = new \DOMDocument();
		$document = $domDoc->createElement('doc');
		$textNode = $domDoc->createTextNode($text);
		$domDoc->appendChild($document);
		$document->appendChild($textNode);

		$this->applyPatterns($textNode);

		$xpath = new \DOMXPath($domDoc);
		$textNodes = $xpath->query('//text()');
		foreach ($textNodes as $textNode)
		{
			if($textNode->parentNode->nodeName !== 'code')
			{
				# don't need the backslash for escaped characters anymore
				$textNode->nodeValue = preg_replace(
					'@\\\\([^ ])@', "\${1}", $textNode->nodeValue
				);
			}
		}

		return $domDoc;
	}

	private function applyPatterns(\DOMText $node, Pattern $parentPattern = null)
	{
		$document = $node->ownerDocument;
		$parentNode = $node->parentNode;
		$textToReplace = $node->nodeValue;
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
					$patternCreatedDom = $pattern->handleMatch($match, $node, $parentPattern);

					# if pattern decides there's no match after examining regex match
					# we can continue
					if (!$patternCreatedDom)
					{
						continue;
					}

					# add text node from text before match
					$textBeforeMatch = substr($textToReplace, 0, $currentByteOffset);
					$parentNode->replaceChild(
						$document->createTextNode($textBeforeMatch), $node
					);

					# applying subpatterns to dom node from match
					$parentNode->appendChild($patternCreatedDom);
					$this->applySubpatterns($patternCreatedDom, $pattern);

					# create text node from text following match
					$textFollowingMatch = substr(
						$textToReplace, strlen($match[0]) + $currentByteOffset
					);
					$textFollowingMatchNode = $parentNode->appendChild(
						$document->createTextNode($textFollowingMatch)
					);
					$this->applyPatterns($textFollowingMatchNode, $parentPattern);

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

	private function applySubpatterns(\DOMNode $node, Pattern $parentPattern)
	{
		if (!$node->hasChildNodes())
		{
			return;
		}

		foreach ($node->childNodes as $childNode)
		{
			if ($childNode instanceof \DOMText)
			{
				$this->applyPatterns($childNode, $parentPattern);
			}
			else
			{
				$this->applySubpatterns($childNode, $parentPattern);
			}
		}
	}
}