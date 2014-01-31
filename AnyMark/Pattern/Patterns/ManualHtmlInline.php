<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;
use ElementTree\Composable;

/**
 * @package AnyMark
 */
class ManualHtmlInline extends Pattern
{
	public function getRegex()
	{
		return
			'@
		(?<=(\s\n)|(\n)|(^)|(\s))
		(?<html>

			<!--(?<comment>.*?)-->

			|

			(?J)
			<(?<tag>\w+)
				(?<attributes>
					(
					\s+
					\w+(=(?:\"[^\"]*?\"|\'[^\']*?\'|[^\'\">\s]+))?
					)*
				)
			>
			(?<content>(([^<]|<(?=[ ])|(?&html))*))
			<\/\g{tag}>

			|

			(?J)
			<(?<tag>hr|div|br)
				(?<attributes>
					(
					\s+
					\w+(=(?:\"[^\"]*?\"|\'[^\']*?\'|[^\'\">\s]+))?
					)*
				)
			[ ]?/?>
		)
		(?(2)(?!(\n|$)))
		(?(3)(?!\n))
			@x';
	}

	public function handleMatch(
		array $match, Composable $parent, Pattern $parentPattern = null
	) {
		if (isset($match['tag']) || isset($match['selfclosing']))
		{
			$element = $this->createElement($match['tag']);
			if ($match['content'] !== '')
			{
				$element->append(
					$this->createText($match['content'])
				);
			}
			$attributes = $this->getAttributes($match['attributes']);
			foreach ($attributes['name'] as $key=>$value)
			{
				$attr = $element->setAttribute(
					$value, $attributes['value'][$key]
				);
				if ($attributes['quote'][$key] === "'")
				{
					$attr->singleQuotes();
				}
			}
		}
		else # a comment
		{
			$element = $this->createComment($match['comment']);
		}

		return $element;
	}

	private function getAttributes($tagPart)
	{
		preg_match_all(
			"@
			[ ]
			(?<name>\w+)
			(
			=
			((?<quote>[\"|'])(?<value>.*?)[\"|'])
			)?
			@x",
			$tagPart,
			$matches
		);

		return $matches;
	}
}