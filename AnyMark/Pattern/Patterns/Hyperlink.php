<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\Patterns;

use AnyMark\Util\InternalUrlBuilder;
use AnyMark\Plugins\LinkDefinitionCollector;
use AnyMark\Pattern\Pattern;
use ElementTree\Composable;

/**
 * @package AnyMark
 */
class Hyperlink extends Pattern
{
	private $linkDefinitions;

	private $internalUrlBuilder;

	public function __construct(
		LinkDefinitionCollector $linkDefinitionCollector,
		InternalUrlBuilder $internalUrlBuilder
	) {
		$this->linkDefinitions = $linkDefinitionCollector;
		$this->internalUrlBuilder = $internalUrlBuilder;
	}

	public function getRegex()
	{
		return
			'@
			(?J)
			(?<=^|\s)
			(?<inline>
				\[(?<anchor>					# anchor text
					(\[(?2)*?\].*?|.+?)			
				)\]
				\n?								# optional line break
				\(
				(?<url>							# url or <url>
					<.*?>
					|
					\S*?(?(?=\()\(\S*?\)\S*?)	# note: url can contain ( & )
				)
				(								# title
				[ \t]+								# space(s)
				(?<quotes>"|\')						# single or double quotes
				(?<title>.+?)						# title text
				\g{quotes}
				[ \t]*								# space(s)
				)?									# title is optional
				\)
			)
			(?=\W|$)

			|

			(?<reference>
				(?<repeatable>
				(?<!\\\)\[(?<anchor>
					(
						[^[]
						|
						(?&repeatable)
					)+?
				)\]
				)
				(
					\s*
					\[(?<id>.*?)\]
				)
			)

			|

			(?<reference_short>
				(?<!\\\)\[(?<anchor>
					(
						[^[]
					)+?
				)\]
			)

			@xs';
	}

	public function handleMatch(
		array $match, Composable $parent, Pattern $parentPattern = null
	) {
		if ($parentPattern == $this)
		{
			return;
		}

		if (isset($match['reference']))
		{
			return $this->createDomForLinkWithDef($match, $parent);
		}
		else
		{
			return $this->createDomForInlineLink($match, $parent);
		}
	}

	private function createDomForLinkWithDef(array $match, Composable $parent)
	{
		if (!isset($match['id']) || ($match['id'] === ''))
		{
			$match['id'] = $match['anchor'];
		}

		$match['id'] = preg_replace("@ *\n *@", " ", $match['id']);
		$linkDef = $this->linkDefinitions->get($match['id']);
		if (!$linkDef)
		{
			return;
		}

		$title = $linkDef->getTitle();
		$url = $linkDef->getUrl();
		$anchorText = $match['anchor'];

		return $this->createDomForLink($url, $anchorText, $title, $parent);
		
	}

	private function createDomForInlineLink(array $match, Composable $parent)
	{
		$url = (isset($match['url'][0]) && ($match['url'][0] == '<'))
			? substr($match['url'], 1, -1) : $match['url'];
		$title = isset($match['title']) ? $match['title'] : null;

		return $this->createDomForLink($url, $match['anchor'], $title, $parent);
	}

	private function createDomForLink($url, $anchor, $title = null, Composable $parent)
	{
		$url = $this->internalUrlBuilder->urlTo($url);

		$urlNode = $this->createElement('a');
		$urlNode->append($this->createText($anchor));
		$urlNode->setAttribute('href', $url);

		if ($title)
		{
			$urlNode->setAttribute('title', $title);
		}
		
		return $urlNode;
	}

	private function isRelative($url)
	{
		$filePart = strstr($url, "#", true);

		if (!$filePart)
		{
			$filePart = $url;
		}

		if (preg_match("#[^a-zA-Z0-9/]#", $filePart))
		{
			return false;
		}

		return true;
	}
}