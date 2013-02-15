<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;
use AnyMark\Processor\Processors\LinkDefinitionCollector;
use AnyMark\ElementTree\ElementTree;

/**
 * @package AnyMark
 */
class Image extends Pattern
{
	private $linkDefinitions;

	public function __construct(LinkDefinitionCollector $linkDefinitionCollector)
	{
		$this->linkDefinitions = $linkDefinitionCollector;
	}

	public function getRegex()
	{
		return
			'@

			(?<inline>(?J)
				!\[(?<alt>.*)\]							# ![alternate text]
				\(										# (
					(?<path>							# path|<path>
						<(\S+?)?>
						|
						(\S+?)?
					)
					([ ]+("|\')(?<title>.+)("|\')[ ]*)?	# "optional title"
				\)										# )
			)

			|

			(?<reference>(?J)
				(?<begin>^|[ ]+)
				!\[(?<alt>.+)\]					# ![alternate text]
				\[(?<id>.+)\]					# [id]
				(?<end>[ ]+|$)
			)
	
			@xU';
	}

	public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
		if (isset($match['reference']))
		{
			return $this->replaceReference($match, $parent);
		}
		else
		{
			return $this->replaceInline($match, $parent);
		}
	}

	private function replaceInline(array $match, ElementTree $parent)
	{
		$path = str_replace('"', '&quot;', $match['path']);
		if (isset($path[0]) && $path[0] === '<')
		{
			$path = substr($path, 1, -1);
		}

		$img = $parent->createElement('img');
		$img->setAttribute('alt', $match['alt']);
		if (isset($match['title']))
		{
			$img->setAttribute('title', $match['title']);
		}
		$img->setAttribute('src', $path);

		return $img;
	}

	/**
	 * @todo replace circular handling
	 */
	private function replaceReference(array $match, ElementTree $parent)
	{
		$linkDefinition = $this->linkDefinitions->get($match['id']);
		if (!$linkDefinition)
		{
			throw new \Exception('Following link definition not found: "['
			. $match['id'] . ']"'
			);
		}
		$title = $linkDefinition->getTitle();
			
		$img = $parent->createElement('img');
		$img->setAttribute('alt', $match['alt']);
		if ($title)
		{
			$img->setAttribute('title', $title);
		}
		$img->setAttribute('src', $linkDefinition->getUrl());

		return $img;
	}
}