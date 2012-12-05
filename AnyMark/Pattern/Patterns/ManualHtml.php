<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;

/**
 * @package AnyMark
 */
class ManualHtml extends Pattern
{
	public function getRegex()
	{
		// @todo add where I found the original regex
		return
			'@
			(<!--(?<comment>.*?)-->)
			|
			(?J)
			(?<=  # see remark about being a paragraph below
				(?<before_blank>..)
				|(?<before_newline>.)
				|(?<before_start>^)
			)
			<(?<tag>\w+)
				(?<attributes>
					(
					\s+
					\w+(=(?:\"[^\"]*?\"|\'[^\']*?\'|[^\'\">\s]+))?
					)*
				)
			>
			(?<content>(([^<]|<(?=[ ])|(?R))*))
			<\/\g{tag}>
			(?=(?<after>..|.|$))
			|
			(?J)
			<(?<tag>hr|div)		# the div is there only for a certain "official" end to end test and its a stupid one :)
				(?<attributes>
					(
					\s+
					\w+(=(?:\"[^\"]*?\"|\'[^\']*?\'|[^\'\">\s]+))?
					)*
				)
			[ ]?/?>
			@xs';
	}

	public function handleMatch(array $match, \DOMNode $parentNode, Pattern $parentPattern = null)
	{
		# some checks to see if it should be a paragraph
		$para = false;

		#	paragraph\n
		# 	\n
		# 	<span>paragraph</span>
		#
		# will be paragraph
		if (isset($match['before_blank']))
		{
			if (
				($match['before_blank'] === "\n\n")
				&& (substr($match['content'], 0, 1) != "\n")
				&& ($match['after'] === "\n\n")
			) {
				$para = true;
			}
		}

		# <span>text starting with paragraph within tags</span>\n
		# \n
		if (
			isset($match['before_start'])
			&& (substr($match['content'], 0, 1) != "\n")
			&& ($match['after'] === "\n\n")
		) {
			$para = true;
		}

		# \n
		# <span>foo</span>\n
		# <span>bar</span>\n
		if (isset($match['before_blank'])) {
			if (
				($match['before_blank'] === "\n\n")
				&& (substr($match['content'], 0, 1) != "\n")
				&& ($match['after'] != "\n\n")
			) {
				$para = true;
			}
		}

		# handling paragraph exceptions
		if (isset($match['tag']))
		{
			if ($match['tag'] === 'hr')
			{
				$para = false;
			}
		}

		if ($para)
		{
			return false;
		}

		$ownerDocument = $this->getOwnerDocument($parentNode);
		if (isset($match['tag']) || isset($match['selfclosing']))
		{
			$element = $ownerDocument->createElement($match['tag']);
			if ($match['content'] !== '')
			{
				$element->appendChild(
					$ownerDocument->createTextNode($match['content'])
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
			$element = $ownerDocument->createComment($match['comment']);
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