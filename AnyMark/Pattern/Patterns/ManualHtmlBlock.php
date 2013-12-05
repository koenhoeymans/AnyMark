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
	protected $blockTags = 'address|article|aside|audio|blockquote|canvas|dd|del|div|dl
			|fieldset|figcaption|figure|footer|form|h1|h2|h3|h4|h5|h6|header|hgroup
			|hr|ins|noscript|ol|output|p|pre|section|table|tfoot|ul|video';

	protected $insDelTags = 'ins|del';

	protected $attributes = '(\s+\w+(=(?:\"[^\"]*?\"|\'[^\']*?\'|[^\'\">\s]+))?)*';

	public function getRegex()
	{
		return
		'@
		(?<=\n|^)
		(?<html>(?J)

			<!--(?<comment>.*?)-->

			|

			(<(?<tag>' . $this->blockTags . ')(?<attributes>' . $this->attributes . ')>
			(?<content>
				(?<content_newline>\n)?\n*
				(
					(?<no_indent>(?<=\n)([^<\s]+))
					|
					\n+
					|
					`[^\n]+?`
					|
					[^<\n\s]
					|
					\t[^\n]+
					|
					\s+
					|
					<(?=[ ])
					|
					<(?<subtag>\w+)' . $this->attributes. '>.*?</\g{subtag}>
					
				)*
			)
			(?<!\n[ ]{4})</\g{tag}>)

			|

			(?<empty><(?<self_closing_tag>hr|div|br)
				(?<attributes>' . $this->attributes . ')[ ]?/?>)
		)
		(?=\s*\n|\s*$)
		@xs';
	}

	public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
		if (empty($match['content_newline']) && !empty($match['tag'])
			&& ($match['tag'] === 'ins' || $match['tag'] === 'del'))
		{
			return;
		}

		if (!empty($match['comment']))
		{
			$element = $parent->createComment($match['comment']);
		}
		else
		{
			$tag = empty($match['tag']) ? $match['self_closing_tag'] : $match['tag'];
			$element = $parent->createElement($tag);

			if (!empty($match['content']))
			{
				$element->append(
					$parent->createText($match['content'])
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

		return $element;
	}

	private function getAttributes($tagPart)
	{
		preg_match_all(
			"@
			[ \n]*
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