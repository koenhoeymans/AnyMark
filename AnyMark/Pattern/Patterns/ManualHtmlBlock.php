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
class ManualHtmlBlock extends Pattern
{
	public function getRegex()
	{
		return
			'@
		(?<=(?<empty_line_before>\n\n)|\n|(?<before_is_start>^))
		(?<html>

			(?<is_comment><!--(?<comment>.*?)-->)

			|

			(?J)
			<(?<tag>\w+)
				(?<attributes_open>
					(
					\s+
					\w+(=(?:\"[^\"]*?\"|\'[^\']*?\'|[^\'\">\s]+))?
					)*
				)
			>
			(?<content>(?<content_newline>\n)?\n*(?<indent>\s*)(([^<]|<(?=[ ])|(?&html))*))
			<\/\g{tag}>

			|

			(?J)
			<(?<self_closing_tag>hr|div)
				(?<attributes_closed>
					(
					\s+
					\w+(=(?:\"[^\"]*?\"|\'[^\']*?\'|[^\'\">\s]+))?
					)*
				)
			[ ]?/?>
		)
		(?=(?<empty_line_after>\n\n)|\s*\n|\s*$)
			@xs';
	}

	public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
		if (isset($match['indent']))
		{
			$match['content'] = preg_replace(
				"@\n" . $match['indent'] . "@", "\n", $match['content']
			);
		}

		if (!empty($match['is_comment']))
		{
			$element = $parent->createComment($match['comment']);
		}
		else
		{
			if (!empty($match['empty_line_before'])
				&& !empty($match['empty_line_after'])
				&& empty($match['content_newline'])
				&& empty($match['self_closing_tag'])
			) {
				return;
			}

			if (!empty($match['empty_line_before'])
				&& empty($match['empty_line_after'])
			) {
				return;
			}

			if (
				isset($match['before_is_start'])
				&& !empty($match['empty_line_after'])
				&& empty($match['content_newline'])
				&& empty($match['self_closing_tag'])
			) {
				return;
			}

			$tag = empty($match['tag']) ? $match['self_closing_tag'] : $match['tag'];
			$element = $parent->createElement($tag);
			if (!empty($match['content']))
			{
				if ((!empty($match['empty_line_before']) || !empty($match['empty_line_after']))
					&& ($match['content_newline'] !== "\n"))
				{
					$match['content'] = "\n" . $match['content'] . "\n";
				}
				$element->append(
					$parent->createText($match['content'])
				);
			}
			$attributes = !empty($match['attributes_closed'])
				? $match['attributes_closed']
				: $match['attributes_open'];
			$attributes = $this->getAttributes($attributes);
			foreach ($attributes['name'] as $key=>$value)
			{
				$element->setAttribute(
					$value, $attributes['value'][$key]
				);
			}
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