<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;
use ElementTree\ElementTree;

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
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
		if (isset($match['tag']) || isset($match['selfclosing']))
		{
			$element = $parent->createElement($match['tag']);
			if ($match['content'] !== '')
			{
				$element->append(
					$parent->createText($match['content'])
				);
			}
			$attributes = $this->getAttributes($match['attributes']);
			foreach ($attributes['name'] as $key=>$value)
			{
				$element->setAttribute(
					$value, $attributes['value'][$key]
				);
			}
		}
		else # a comment
		{
			$element = $parent->createComment($match['comment']);
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
			([\"|'](?<value>.*?)[\"|'])
			)?
			@x",
			$tagPart,
			$matches
		);

		return $matches;
	}
}